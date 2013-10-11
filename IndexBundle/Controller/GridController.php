<?php 
namespace RGM\eLibreria\IndexBundle\Controller;

use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GridController extends Controller{
	private $path = 'RGMELibreriaIndexBundle:Grid:';
	private $plantilla_grid = 'grid.html.twig';
	
	private $controller;
	
	private $nombreEntidad = null; //Como: IbercoolQMCalidadEmpresaBundle:Empleado
	private $opcionesGrid = null;
	
	private $source = null;
	private $grid = null;
	
	private $grid_boton_editar;
	private $grid_ruta_editar;
	
	private $grid_boton_borrar;
	private $grid_ruta_borrar;
	private $grid_confirmar_borrar;
	
	public function __construct($nombreEntidad, $controller){
		$this->nombreEntidad = $nombreEntidad;
		$this->controller = $controller;
		
		$matches = array();
		$infoController = $controller->getRequest()->attributes->get('_controller');				
		preg_match('/Controller\\\(.*)::/', $infoController, $matches);
		
		$this->source = new Entity($nombreEntidad);
		$this->grid = $controller->get('grid');
		$this->grid->setId($matches[1]);
		$this->grid->setSource($this -> source);
	}
	
	public function setOpciones($opciones){
		//Opciones minimas: grid.
		//Otras opciones: plantilla y ventanaModal.
		$this -> opcionesGrid = $this -> configOpciones($opciones);
		
		$this -> configGrid();
		
		return $this;
	}
	
	private function configOpciones($opciones){
		$res = $opciones;
		
		//Opciones del Grid
		if(!isset($opciones['grid_boton_editar'])){
			$res['grid_boton_editar'] = 'Editar';
		}
		
		if(!isset($opciones['grid_ruta_editar'])){
			$res['grid_ruta_editar'] = null;
		}
		
		if(!isset($opciones['grid_boton_borrar'])){
			$res['grid_boton_borrar'] = 'Borrar';
		}
		
		if(!isset($opciones['grid_ruta_borrar'])){
			$res['grid_ruta_borrar'] = null;
		}
		
		if(!isset($opciones['grid_confirmar_borrar'])){
			$res['grid_confirmar_borrar'] = '¿Realmente desea realizar la accion a la fila seleccionada?';
		}
		
		if(!isset($opciones['grid_limites'])){
			$res['grid_limites'] = array(5,10,15);
		}
		
		return $res;
	}
	
	private function configGrid(){				
		if($this->opcionesGrid['grid_ruta_editar'] != null){
			$accionEditar = new RowAction($this -> opcionesGrid['grid_boton_editar'], $this -> opcionesGrid['grid_ruta_editar'], false, '_self', array('class' => 'editar'));
			$this -> grid -> addRowAction($accionEditar);
		}
		
		if($this->opcionesGrid['grid_ruta_borrar'] != null){
			$accionBorrar = new RowAction($this -> opcionesGrid['grid_boton_borrar'], $this -> opcionesGrid['grid_ruta_borrar'], true, '_self', array('class' => 'borrar'));
			$accionBorrar -> setConfirmMessage($this -> opcionesGrid['grid_confirmar_borrar']);
			$this -> grid -> addRowAction($accionBorrar);
		}
				
		
		$this -> grid -> addMassAction(new DeleteMassAction(true));
		
		$this -> grid -> setActionsColumnSize(50);
		
		$this -> grid -> setLimits($this -> opcionesGrid['grid_limites']);
	}
	
	public function getGrid(){
		return $this -> grid;
	}
	
	public function getSource(){
		return $this -> source;
	}
	
	public function addColumn($id, $titulo, $pos = 0){
		$columnaEstado = new BlankColumn(array('id' => $id, 'title' => $titulo));
		$this->grid->addColumn($columnaEstado, $pos);
	}
	
	private function setVista(){
		$sesion = $this->controller->getRequest()->getSession()->get('vistas_grid');
		
		if($sesion != null){
			if(array_key_exists($this->grid->getHash(), $sesion)){
				$this->grid->setDefaultPage($sesion[$this->grid->getHash()]['page'] + 1);
				$this->grid->setDefaultLimit($sesion[$this->grid->getHash()]['limit']);
			}
		}
	}
	
	private function guardarVista($page, $limit){
		$sesion = $this->controller->getRequest()->getSession()->get('vistas_grid');
		
		$sesion[$this->grid->getHash()] = array(
				'page' => $page,
				'limit' => $limit
		); 
		
		$this->controller->getRequest()->getSession()->set('vistas_grid', $sesion);
	}
	
	public function getRender($plantilla){	
		$this->setVista();
		
		$render = $this->grid->getGridResponse($plantilla, $this->opcionesGrid);
		
		$this->guardarVista($this->grid->getPage(), $this->grid->getLimit());
				
		return $render;
	}
	
	public function getRenderAjax(){		
		return $this->getRender($this->path . $this->plantilla_grid);
	}
}
?>