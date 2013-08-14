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
	private $plantilla_verGrid = 'verGrid.html.twig';
	
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
		
		$this->source = new Entity($nombreEntidad);
		$this->grid = $controller->get('grid');
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
			$res['grid_limites'] = array(6,10,15);
		}
		
		return $res;
	}
	
	private function configGrid(){				
		if($this->opcionesGrid['grid_ruta_editar'] != null){
			$accionEditar = new RowAction($this -> opcionesGrid['grid_boton_editar'], $this -> opcionesGrid['grid_ruta_editar'], false, '_self', array('class' => 'editar'));
		}
		
		if($this->opcionesGrid['grid_ruta_borrar'] != null){
			$accionBorrar = new RowAction($this -> opcionesGrid['grid_boton_borrar'], $this -> opcionesGrid['grid_ruta_borrar'], true, '_self', array('class' => 'borrar'));
			$accionBorrar -> setConfirmMessage($this -> opcionesGrid['grid_confirmar_borrar']);
		}
				
		$this -> grid -> addRowAction($accionBorrar);
		$this -> grid -> addRowAction($accionEditar);
		
		$this -> grid -> addMassAction(new DeleteMassAction(true));
		
		$this->grid->setActionsColumnSize(50);
		
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
	
	public function getRender(){		
		return $this->grid->getGridResponse($this -> path . $this -> plantilla_verGrid, $this->opcionesGrid);;
	}
	
	public function getRenderAjax(){
		return $this->grid->getGridResponse($this -> path . $this -> plantilla_grid, $this->opcionesGrid);
	}
	
	public function getRenderVentanaModal(){
		$this->grid->isReadyForRedirect();
		
		$this->opcionesGrid['grid'] = $this->grid;
		
		$ventanaModal = new VentanaModal(
				$this -> path,
				$this -> opcionesGrid);
		
		return $ventanaModal -> renderVentanaModal($this -> controller);
	}
}
?>