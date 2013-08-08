<?php
namespace RGM\eLibreria\IndexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;

class AsistenteController extends Controller {
	private $nombreLogicoConfigBasica = 'RGMELibreriaIndexBundle';
	private $entidad_configBasica = 'ConfigBasica';
	private $alias_configBasica = 'a';

	private $ruta_inicio = null;
	private $nombreLogico = null;
	private $path = null;
	private $seccion = null;
	private $subseccion = null;
	
	private $nombreEntidad = null;

	private $usuario = null;
	private $empleado = null;

	private $orderByEntidades = null;

	private $opciones_plantilla = null;

	private $nombreFormularios = array();

	private static $opcionesGlobales = array();
	
	private $plantilla_grid = 'RGMELibreriaIndexBundle::grid.html.twig';

	protected function __construct($inicio, $nombreLogico, $path, $seccion = null,
			$subseccion = null) {
		$this->ruta_inicio = $inicio;
		$this->nombreLogico = $nombreLogico;
		$this->path = $nombreLogico . ':' . $path;
		$this->seccion = $seccion;
		$this->subseccion = $subseccion;
	}
	
	

	private function getOpcionGlobal($nombre) {
		if (!array_key_exists($nombre, AsistenteController::$opcionesGlobales)) {
			AsistenteController::$opcionesGlobales[$nombre] = $this->getEm()
					->getRepository(
							$this->nombreLogicoConfigBasica . ':'
									. $this->entidad_configBasica)
					->findOneBy(array('tipo' => $nombre))->getValor();
		}

		return AsistenteController::$opcionesGlobales[$nombre];
	}

	protected function getInicio() {
		return $this->ruta_inicio;
	}

	protected function getNombreLogico() {
		return $this->nombreLogico;
	}

	protected function getPath() {
		return $this->path;
	}
	
	protected function setEntidad($e){
		$this->nombreEntidad = $this -> nombreLogico . ':' . $e;
		
		return $this;
	}
	
	protected function getNombreEntidad(){
		return $this->nombreEntidad;
	}

	protected function getSeccion() {
		return $this->seccion;
	}

	protected function setSeccion($seccion) {
		$this->seccion = $seccion;
	}

	protected function getSubseccion() {
		return $this->subseccion;
	}

	protected function setSubseccion($subseccion) {
		$this->subseccion = $subseccion;
	}

	protected function getLimitePaginado() {
		return $this->getOpcionGlobal('limite_paginado');
	}

	protected function getTitulo() {
		return $this->getOpcionGlobal('titulo');
	}

	protected function getRutaInicio() {
		return $this->generateUrl($this->ruta_inicio);
	}

	protected function irInicio() {
		return $this->redirect($this->getRutaInicio());
	}

	protected function getRutaInicioPaginado($page) {
		return $this->generateUrl($this->ruta_inicio, array('page' => $page));
	}

	protected function irInicioPaginado($page) {
		return $this->redirect($this->getRutaInicioPaginado($page));
	}

	protected function getEm() {
		return $this->get('doctrine')->getManager();
	}

	protected function setFlash($msg) {
		$this->get('session')->getFlashBag()->add('info', $msg);
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

	protected function getDatos($entity, $alias, $page) {
		$em = $this->getEm();
		$qb = $em->getRepository($this->getNombreLogico() . ':' . $entity)
				->createQueryBuilder($alias);

		$arrayEntidad = $this->getOrdenEntidades();

		if (array_key_exists($entity, $arrayEntidad)) {
			$orderBy = $arrayEntidad[$entity];

			foreach ($orderBy as $key => $value) {
				$qb->addOrderBy($alias . '.' . $key, $value);
			}
		}

		$adaptador = new DoctrineOrmAdapter($qb);

		return new Pager($adaptador,
				array('page' => $page, 'limit' => $this->getLimitePaginado()));
	}

	protected function getNuevaInstancia($nombreClase, $args = null) {
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

	protected function setFormularios(array $nombreFormularios) {
		$this->nombreFormularios = $nombreFormularios;

		return $this;
	}

	protected function getFormulario($nombre) {
		$res = null;

		if (array_key_exists($nombre, $this->nombreFormularios)) {
			$res = $this
					->getNuevaInstancia($this->nombreFormularios[$nombre]);
		}

		return $res;
	}

	protected function getOrdenEntidades() {
		if ($this->orderByEntidades == null) {
			$this->orderByEntidades['Empleado'] = array('apellidos' => 'ASC',
					'nombre' => 'ASC');
		}

		return $this->orderByEntidades;
	}

	protected function getOpcionesPlantilla() {
		if ($this->opciones_plantilla == null) {
			$this->opciones_plantilla['titulo'] = $this->getTitulo();
			$this->opciones_plantilla['empleado'] = $this->getEmpleado();
			$this->opciones_plantilla['seccion'] = $this->getSeccion();
			$this->opciones_plantilla['subseccion'] = $this->getSubseccion();
		}

		return $this->opciones_plantilla;
	}
	
	public function getPlantillaGrid(){
		return $this -> plantilla_grid;
	}
}
?>