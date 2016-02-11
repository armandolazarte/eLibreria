<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\GridController;
use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use APY\DataGridBundle\Grid\Column\BlankColumn;

class VentaController extends Asistente{
	private $bundle = 'ventasbundle';
	private $controlador = 'venta';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	private function getGrid(){
		$infoEntidad = $this->getParametro('entidad');
		$grid = new GridController($this->getEntidadLogico($infoEntidad['repositorio']), $this);
		
		$grid->getGrid()->getColumn('id')->setOrder('desc');

		$coste = new BlankColumn(array('id' => 'cos', 'title' => 'Coste', 'size' => '100', 'safe' => false));
		$beneficio = new BlankColumn(array('id' => 'ben', 'title' => 'Beneficio', 'size' => '100', 'safe' => false));
		$ticket = new BlankColumn(array('id' => 'ticket', 'title' => 'Ver Ticket', 'size' => '100', 'safe' => false));
		
		$grid->getGrid()->addColumn($beneficio, 4);
		$grid->getGrid()->addColumn($coste, 4);
		$grid->getGrid()->addColumn($ticket, 10);
		
		$controlador = $this;
		$grid->getSource()->manipulateRow(function($row) use($controlador){
			$entidad = $row->getEntity();
		
			$tot = $row->getField('total');
			
			$row->setField('total', number_format($tot, 2, '.', '') . '€');
			
			$cos = 0;
			
			foreach($entidad->getItems() as $item){
				$existencia = $item->getExistencia();
				$itemAlb = $existencia->getItemAlbaran();
				
				if($itemAlb){
					$cos += ($existencia->getPrecio() * (1 - $itemAlb->getDescuento()) * (1 + $existencia->getIva()));
				}
			} 
			
			$ruta_ticket = $this->generateUrl($this->getParametro('ruta_ticket'), array("idVenta"=>$entidad->getId()));
			$link_ticket = "<a href=\"".$ruta_ticket."\" onclick=\"window.open(this.href, this.target, 'menubar=no,width=500,height=600'); return false;\" target=\"_blank\">Ver Ticket</a>";
			
			$row->setField('ticket', $link_ticket);
			$row->setField('cos', number_format($cos, 2, '.', '') . '€' );
			$row->setField('ben', number_format($tot - $cos, 2, '.', '') . '€' );
		
			return $row;
		});
			
		return $grid;
	}
	
	private function getOpcionesGridAjax(){
		return $this->getArrayOpcionesGridAjax(
				$this->getParametro('grid_boton_editar'),
				$this->getParametro('grid_ruta_editar'),
				$this->getParametro('grid_boton_borrar'),
				$this->getParametro('grid_ruta_borrar'),
				$this->getParametro('msg_confirmar_borrar'));
	}
	
	private function getOpcionesVista(){
		$opciones = $this->getArrayOpcionesVista($this->getOpcionesGridAjax());
		$opciones['ruta_form_crear'] = $this->getParametro('ruta_form_crear');
		$opciones['titulo_crear'] = $this->getParametro('titulo_crear');
	
		return $opciones;
	}
	
	public function verVentasAction(Request $peticion){
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
	
	public function borrarVentaAction(Request $peticion, $id){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$infoEntidad = $this->getParametro('entidad');
		$entidad = $em->getRepository($this->getEntidadLogico($infoEntidad['repositorio']))->find($id);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_borrar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_borrar'), array("id"=>$id));
		$opcionesVM['titulo_submit'] = $this->getParametro('titulo_submit_borrar');
		$opcionesVM['msg'] = $this->getParametro('msg_borrar');
		$opcionesVM['msg_confirmar'] = $this->getParametro('msg_confirmar_borrar');
	
		$opcionesVM['form'] = $this->createForm($this->getFormulario('visor'), $entidad);
	
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
	
			if($opcionesVM['form']->isValid()){
				foreach($entidad->getItems() as $item){
					$existencia = $item->getExistencia();
					$item->setExistencia(null);
					
					$existencia->setVendido(0);
					$em->persist($existencia);
				}
				
				$em->remove($entidad);
				$em->flush();
	
				$this->setFlash($this->getParametro('flash_borrar'));
				return $this->irInicio();
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
}
?>