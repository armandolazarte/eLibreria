<?php 
namespace RGM\eLibreria\FinanciacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Component\HttpFoundation\Request;

class GastosCorrientesController extends Asistente{
	private $bundle = 'financiacionbundle';
	private $controller = 'gastos';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this);

		$columnaPago = new BlankColumn(array('id'=>'pagos', 'title'=>'Pagos', 'safe'=>false));
		$columnaEstable = new BlankColumn(array('id'=>'estable', 'title'=>'Pago Estable', 'safe'=>false));
		$columnaMensualidad = new BlankColumn(array('id'=>'mensualidad', 'title'=>'Mensualidad', 'safe'=>false));

		$grid->getGrid()->addColumn($columnaPago);
		$grid->getGrid()->addColumn($columnaEstable);
		$grid->getGrid()->addColumn($columnaMensualidad);
		
		$controlador = $this;
		
		$grid->getSource()->manipulateRow(
				function($row) use($controlador){
					$entidad = $row->getEntity();

					$enlace_pagos = $controlador->generateUrl($controlador->getParametro('ruta_pagos'), array('idGasto' => $entidad->getId()));
					$enlace_crear_pago = $controlador->generateUrl($controlador->getParametro('ruta_form_pagos_crear'), array('idGasto' => $entidad->getId()));
					$salida_pagos = '<div class="jaula-botones"><a class="enlace_icono ver" title="Ver Pagos" href="'.$enlace_pagos.'">Ver Pagos</a><a class="enlace_icono nuevo" title="Crear nuevo pago" href="'.$enlace_crear_pago.'">Crear nuevo pago</a></div>';
										
					$row->setField('pagos', $salida_pagos);
					
					$row->setField('estable', $entidad->getEstableString());
					
					$row->setField('mensualidad', $entidad->getMensualidad());
					
					return $row;
				}
		);
		
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
	
	public function verGastosCorrientesAction(Request $peticion){
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
	
	public function crearGastosCorrientesAction(Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_crear');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$entidad = $this->getNuevaInstancia($this->getParametro('clase_entidad'));
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('ruta_form_crear'));
		$opcionesVM['titulo_submit'] = $this->getParametro('titulo_submit_crear');
		$opcionesVM['form'] = $this->createForm($this->getFormulario('editor'), $entidad);
	
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
				
			if($opcionesVM['form']->isValid()){
				$em->persist($entidad);
				$em->flush();
	
				$this->setFlash($this->getParametro('flash_crear'));
				return $this->irInicio();
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
	public function editarGastosCorrientesAction($id, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_editar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_editar'), array("id"=>$id));
		$opcionesVM['titulo_submit'] = $this->getParametro('grid_boton_editar');
		$opcionesVM['form'] = $this->createForm($this->getFormulario('editor'), $entidad);
	
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
	
			if($opcionesVM['form']->isValid()){
				$em->persist($entidad);
				$em->flush();
	
				$this->setFlash($this->getParametro('flash_editar'));
				return $this->irInicio();
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
	public function borrarGastosCorrientesAction($id, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
	
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