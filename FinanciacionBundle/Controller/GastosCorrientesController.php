<?php 
namespace RGM\eLibreria\FinanciacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;

class GastosCorrientesController extends AsistenteController{
	
	private $seccion = 'Gestor de Finanzas';
	private $subseccion = 'Gastos corrientes';
	
	private $logicoBundle = 'RGMELibreriaFinanciacionBundle';
	private $ruta_inicio = 'rgm_e_libreria_financiacion_gastos_corrientes_homepage';
	
	private $entidad = 'GastoCorriente';
	private $entidad_clase = 'RGM\eLibreria\FinanciacionBundle\Entity\GastoCorriente';
	private $alias = 'gc';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\GastoCorriente\GastoCorrienteType',
			'visor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\GastoCorriente\GastoCorrienteVisorType'
	);
	
	private $ruta_form_crear = 'rgm_e_libreria_financiacion_gastos_corrientes_crear';
	private $titulo_crear = 'Crear Gasto Corriente';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Gasto Corriente creado con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgm_e_libreria_financiacion_gastos_corrientes_editar';
	private $titulo_editar = 'Editar Gasto Corriente';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Gasto Corriente editado con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgm_e_libreria_financiacion_gastos_corrientes_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Gasto Corriente';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el gasto corriente?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Gasto Corriente borrado con exito';
	
	public function __construct(){
		parent::__construct(
				$this->ruta_inicio, 
				$this->logicoBundle,  
				$this->seccion,
				$this->subseccion,
				$this->entidad,
				$this->nombreFormularios);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getNombreEntidad(), $this);

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

					$salida_pagos = '<ul class="pagos">';
					//$salida_pagos .= '<li><a class="" href="'.$controlador->generateUrl('rgm_e_libreria_financiacion_gastos_corrientes_pago').'">Realizar nuevo pago</a></li>';
					//$salida_pagos .= '<li><a class="" href="'.$controlador->generateUrl('rgm_e_libreria_financiacion_gastos_corrientes_historial').'">Ver historial</a></li>';
					$salida_pagos .= '</ul>';
					
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
				$this -> grid_boton_editar,
				$this -> grid_ruta_editar,
				$this -> grid_boton_borrar,
				$this -> grid_ruta_borrar,
				$this -> msg_confirmar_borrar);
	}
	
	private function getOpcionesVista(){	
		return $this->getArrayOpcionesVista(
				$this->ruta_form_crear,
				$this->getOpcionesGridAjax());
	}
	
	public function verGastosCorrientesAction(){
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
	
	public function crearGastosCorrientesAction(){
		$peticion = $this->getRequest();
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();
		
		$opciones = $this->getOpcionesVista();

		$opciones['titulo_ventana'] = $this->titulo_crear;
		$opciones['titulo_submit'] = $this->titulo_submit_crear;
		$opciones['path_form'] = $this->generateUrl($this->ruta_form_crear);
		
		$entidad = $this->getNuevaInstancia($this->entidad_clase);
		
		$opciones['form'] = $this->createForm($this->getFormulario('editor'), $entidad);
		
		if($peticion->getMethod() == "POST"){
			$opciones['form']->bind($peticion);
			
			if($opciones['form']->isValid()){
				$em->persist($entidad);
				$em->flush();
				
				$this->setFlash($this->flash_crear);
				return $this->irInicio();
			}
		}
		
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
		
		return $grid->getRenderVentanaModal();
	}
	
	public function editarGastosCorrientesAction($id){
		$peticion = $this->getRequest();
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();
		$entidad = $em->getRepository($this->getNombreEntidad())->find($id);
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		$opciones = $this->getOpcionesVista();
		
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_editar, array('id' => $id));
		$opciones['titulo_ventana'] = $this -> titulo_editar;
		$opciones['titulo_submit'] = $this -> titulo_submit_editar;
		
		$opciones['form'] = $this -> createForm($this -> getFormulario('editor'), $entidad);
			
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
		
			if($opciones['form'] -> isValid()){
				$em -> persist($entidad);
				$em -> flush();
					
				$this -> setFlash($this -> flash_editar);
				return $this -> irInicio();
			}
		}
			
		$grid = $this -> getGrid();
		$grid -> setOpciones($opciones);
		
		return $grid -> getRenderVentanaModal();
	}
	
	public function borrarGastosCorrientesAction($id){
		$peticion = $this->getRequest();
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();
		$entidad = $em->getRepository($this->getNombreEntidad())->find($id);
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		$opciones = $this->getOpcionesVista();
		
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_borrar, array('id' => $id));
		$opciones['titulo_ventana'] = $this -> titulo_borrar;
		$opciones['msg'] = $this -> msg_borrar;
		$opciones['titulo_form'] = $this -> titulo_form_borrar;
		$opciones['msg_confirmar'] = $this -> msg_confirmar_borrar;
		$opciones['titulo_submit'] = $this -> titulo_submit_borrar;
		
		$opciones['form'] = $this->createForm($this->getFormulario('visor'), $entidad);
		
		if($peticion->getMethod() == "POST"){
			$opciones['form']->bind($peticion);
			
			if($opciones['form']->isValid()){
				$em->remove($entidad);
				$em->flush();
				
				$this->setFlash($this->flash_borrar);
				return $this->irInicio();
			}
		}
		
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
		
		return $grid->getRenderVentanaModal();
	}
	
}
?>