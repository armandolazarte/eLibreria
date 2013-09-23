<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;

class VentaController{
	private $seccion = 'Gestor de Ventas';
	private $subseccion = 'Ventas';
		
	private $logicoBundle = 'RGMELibreriaVentasBundle';
	private $ruta_inicio = 'rgm_e_libreria_ventas_homepage';
	
	private $entidad = 'Venta';
	private $alias = 'v';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\AlbaranType',
			'importar' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\ImportarAlbaranesType'
	);
	
	private $menu = 'RGMELibreriaVentasBundle::menu.html.twig';
	
	public function __construct(){
		parent::__construct(
				$this->ruta_inicio, 
				$this->logicoBundle, 
				$this->seccion, 
				$this->subseccion, 
				$this->entidad, 
				$this->nombreFormularios, 
				$this->menu);
	}
	
	public function getGrid(){
		$grid = new GridController($this->getNombreEntidad(), $this);
		
		return $grid;
	}
	
	public function getOpcionesGridAjax(){
		return $this->getArrayOpcionesGridAjax(
				$this -> grid_boton_editar,
				$this -> grid_ruta_editar,
				null,
				null,
				null);
	}
	
	public function getOpcionesVista(){
		return $this->getArrayOpcionesVista(
				null,
				$this->getOpcionesGridAjax());
	}
	
	public function indexAction(){
		$peticion = $this->getRequest();
	
		$grid = $this->getGrid();
		$render = null;
	
		if($peticion->isXmlHttpRequest()){
			$grid->setOpciones($this->getOpcionesGridAjax());
			$render = $grid->getRenderAjax();
		}
		else{
			$grid->setOpciones($this->getOpcionesVista());
			$render = $grid->getRender();
		}
	
		return $render;
	}
}
?>