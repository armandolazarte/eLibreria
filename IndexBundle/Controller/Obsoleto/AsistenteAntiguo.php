<?php 
namespace RGM\eLibreria\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Asistente extends Controller{
	private $bundle;
	private $controlador;	
	private $parametros = null;
	
	private $usuario = null;
	private $empleado = null;
	
	private $em = null;
	private $logger = null;
	private $mailer = null;
	
	private $opciones_plantilla = null;
	
	private $nombreLogicoConfigBasica = 'RGMELibreriaIndexBundle';
	private $entidad_configBasica = 'ConfigBasica';
	private $alias_configBasica = 'a';

	private static $opcionesGlobales = array();
	
	protected function __construct($bundle, $controlador){
		$this->bundle = $bundle;
		$this->controlador = $controlador;
	}
	
	private function getParametros(){
		if($this->parametros == null){
			$this->parametros = $this->container->getParameter($this->bundle . '.' . $this->controlador);
		}
		
		return $this->parametros;
	}
	
	protected function getParametro($key){
		$parametros = $this->getParametros();
		$res = null;
		
		if(array_key_exists($key, $parametros)){
			$res = $parametros[$key];
		}
		
		return $res;
	}
	
	private function getOpcionGlobal($nombre) {
		if (!array_key_exists($nombre, self::$opcionesGlobales)) {
			self::$opcionesGlobales[$nombre] = $this->getEm()
					->getRepository(
							$this->nombreLogicoConfigBasica . ':'
									. $this->entidad_configBasica)
					->findOneBy(array('tipo' => $nombre))->getValor();
		}

		return self::$opcionesGlobales[$nombre];
	}

	protected function getTitulo() {
		return $this->getOpcionGlobal('titulo');
	}
	
	
	protected function getUsuario() {
		if ($this->usuario == null) {
			$this->usuario = $this->get('security.context')->getToken()
					->getUser();
		}

		return $this->usuario;
	}

	protected function getEmpleado() {
		if ($this->empleado == null) {
			$this->empleado = $this->getUsuario()->getEmpleado();
		}

		return $this->empleado;
	}
	
	protected function getEm(){
		return $this->get('doctrine')->getManager();
	}

	protected function getLogger(){
		if ($this->logger == null) {
			$this->logger = $this->get('logger');
		}

		return $this->logger;
	}
	
	protected function getMailer(){}
	
	protected function isGranted($rol){
		return $this->get('security.context')->isGranted($rol);
	}
	
	protected function setFlash($msg){
		$this->get('session')->getFlashBag()->add('info', $msg);
	}
	
	protected function getNuevaInstancia($nombreClase, $args = null){
		$class = new \ReflectionClass($nombreClase);
		
		$res = null;
		
		if($args == null){
			$res = $class->newInstance();
		}
		else{
			$res = $class->newInstanceArgs($args);
		}
		
		return $res;
	}
	
	protected function irInicio(){
		return $this->redirect($this->generateUrl($this->getParametro('inicio')));
	}
	
	protected function getFormulario($nombre){
		$res = null;
		$formularios = $this->getParametro('formularios');
		
		if (array_key_exists($nombre, $formularios)) {
			$res = $this
					->getNuevaInstancia($formularios[$nombre]);
		}

		return $res;
	}
	
	protected function getOpcionesPlantilla(){
		if ($this->opciones_plantilla == null) {
			$this->opciones_plantilla['empleado'] = $this->getEmpleado();
			$this->opciones_plantilla['rutaInicio'] = $this->getParametro('inicio');
			$this->opciones_plantilla['titulo'] = $this->getTitulo();
			$this->opciones_plantilla['seccion'] = $this->getParametro('seccion');
			$this->opciones_plantilla['subseccion'] = $this->getParametro('subseccion');
		}

		return $this->opciones_plantilla;
	}
	
	protected function getArrayOpcionesGridAjax($gbe, $gre, $gbb, $grb, $mcb = null, $l = array(6, 10, 15, 30, 60, 100, 200, 500, 1000)){
		$res = array();
	
		$res['grid_boton_editar'] = $gbe;
		$res['grid_ruta_editar'] = $gre;
		$res['grid_boton_borrar'] = $gbb;
		$res['grid_ruta_borrar'] = $grb;
		$res['grid_confirmar_borrar'] = $mcb;
		$res['grid_limites'] = $l;
	
		return $res;
	}
	
	protected function getArrayOpcionesVista($rfc, $opcionesAjax, $menu_subseccion = null, $menu_izq = null){
		$res = $opcionesAjax;
		$opcionesPlantilla = $this->getOpcionesPlantilla();
	
		foreach($opcionesPlantilla as $clave => $valor){
			$res[$clave] = $valor;
		}
	
		$res['titulo_seccion'] = $this->getParametro('seccion');
		$res['titulo_subseccion'] = $this->getParametro('subseccion');
		$res['ruta_crear'] = $rfc;
		$res['menu_seccion'] = $menu_subseccion;
		$res['menu_izquierda'] = $menu_izq;
	
		return $res;
	}
	
	protected function getNombreEntidad(){
		return $this->getParametro('logicoBundle') . ':' . $this->getParametro('entidad');
	}
}
?>