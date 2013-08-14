<?php 
namespace RGM\eLibreria\FinanciacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;

class AvalController extends AsistenteController{
	
	private $seccion = 'Gestor de Finanzas';
	private $subseccion = 'Avales';
	
	private $logicoBundle = 'RGMELibreriaFinanciacionBundle';
	private $ruta_inicio = 'rgm_e_libreria_financiacion_aval_homepage';
	
	private $entidad = 'Aval';
	private $entidad_clase = 'RGM\eLibreria\FinanciacionBundle\Entity\Aval';
	private $alias = 'a';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\Aval\AvalType',
			'visor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\Aval\AvalVisorType'
	);
	
	private $ruta_form_crear = 'rgm_e_libreria_financiacion_aval_crear';
	private $titulo_crear = 'Crear Aval';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Aval creado con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgm_e_libreria_financiacion_aval_editar';
	private $titulo_editar = 'Editar Aval';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Aval editado con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgm_e_libreria_financiacion_aval_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Aval';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el aval?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Aval borrado con exito';
	
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
			
		//Configuracion grid
		$columnaFin = new BlankColumn(array('id' => 'fin', 'title' => 'Fecha de Finalizacion'));
		$columnaDistribuidoras = new BlankColumn(array('id' => 'dist', 'title' => 'Distribuidoras', 'safe' => false));

		$grid->getGrid()->addColumn($columnaFin);
		$grid->getGrid()->addColumn($columnaDistribuidoras);
		
		$grid->getSource()->manipulateRow(
			function($row){
				$entidad = $row->getEntity();
				
				$dist = $entidad->getDistribuidoras();
				$salida_dist = '-';
				
				if(!$dist->isEmpty()){
					$salida_dist = '<ul class="distribuidoras">';
					
					foreach($dist as $d){
						$salida_dist .= '<li>' . $d . '</li>';
					}
					
					$salida_dist .= '</ul>';
				}
				
				$row->setField('fin', $entidad->getFechaDevolucion());
				$row->setField('dist', $salida_dist);
				
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
	
	public function verAvalesAction(){
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
	
	public function crearAvalAction(){
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
	
	public function editarAvalAction($id){
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
	
	public function borrarAvalAction($id){
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