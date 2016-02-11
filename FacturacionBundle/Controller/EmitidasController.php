<?php 
namespace RGM\eLibreria\FacturacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use RGM\eLibreria\IndexBundle\Controller\GridController;

class EmitidasController extends Asistente{
	private $bundle = 'facturacionbundle';
	private $controlador = 'emitidas';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this);
	
		return $grid;
	}
	
	private function getOpcionesGridAjax(){
		return $this->getArrayOpcionesGridAjax(
				$this->getParametro('grid_boton_editar'),
				$this->getParametro('grid_ruta_editar'),
				null,
				null,
				null);
	}
	
	private function getOpcionesVista(){
		$opciones = $this->getArrayOpcionesVista($this->getOpcionesGridAjax());
		$opciones['ruta_form_crear'] = $this->getParametro('ruta_form_crear');
		$opciones['titulo_crear'] = $this->getParametro('titulo_crear');
	
		return $opciones;
	}
	
	public function verFacturasAction(Request $peticion){
		$render = null;
		$grid = $this->getGrid();
	
		if($peticion->isXmlHttpRequest()){
			$grid->setOpciones($this->getOpcionesGridAjax());
			$render = $grid->getRenderAjax();
		}
		else{
			$grid->setOpciones($this->getOpcionesVista());
			$render = $grid->getRender($this->getPlantilla('principal'));
		}
	
		return $render;	
	}
	
	public function crearFacturaAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista();
		
		$ajax = $this->getParametro('ajax');

		foreach($ajax as $k => $v){
			$ajax[$k] = $this->generateUrl($v);
		}
		
		$opciones['ajax'] = $ajax;
		
		$em = $this->getEm();
		$repCli = $em->getRepository('RGMeLibreriaFacturacionBundle:ClienteFacturacion');
		
		//$opciones['clientes'] = $repCli->findAll();
		
		$opciones['clientes'] = array(
				array(
						'id' => '1', 
						'nombre' => 'op1'),
				array(
						'id' => '2', 
						'nombre' => 'op2'),
		);
		
		return $this->render($this->getPlantilla('editor'), $opciones);
	}
}

?>