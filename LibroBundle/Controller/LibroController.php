<?php

namespace RGM\eLibreria\LibroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\VentanaModal;
use APY\DataGridBundle\Grid\Source\Entity;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;

class LibroController extends AsistenteController
{
	private $entidad = 'Libro';
	private $entidad_clase = 'RGM\eLibreria\LibroBundle\Entity\Libro';
	private $alias = 'l';
	
	private $plantilla_verDatos = 'verLibros.html.twig';
	
	private $nombreFormularios = array(
			'creador' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroCrearMasivoType',
			'editor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroType',
			'visor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroVisorType'
	);	
	
	private $ruta_form_crear = 'rgarcia_entrelineas_libro_crear';
	private $titulo_crear = 'Crear Libro';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Objetivo creado con exito';
	
	private $grid_titulo_accion_editar = 'Editar';
	private $ruta_form_editar = 'rgarcia_entrelineas_libro_editar';
	private $titulo_editar = 'Editar Libro';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Libro editado con exito';

	private $grid_titulo_accion_borrar = 'Borrar';
	private $ruta_form_borrar = 'rgarcia_entrelineas_libro_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Libro';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el libro?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Libro borrado con exito';
	
	public function __construct(){
		parent::__construct(
				'rgarcia_entrelineas_libro_homepage', 
				'RGMELibreriaLibroBundle', 
				'Libro:', 
				'Libros', 
				'Gestion de Libros');
		
		$this -> setFormularios($this -> nombreFormularios);
	}
	
	private function getDatosAVisualizar(){
		 // Creates simple grid based on your entity (ORM)
        $source = new Entity($this -> getNombreLogico() . ':' . $this -> entidad);

        // Get a grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);
        
        $accionEditar = new RowAction($this -> grid_titulo_accion_editar, $this -> ruta_form_editar, false, '_self', array('class' => 'editar'));
        $accionBorrar = new RowAction($this -> grid_titulo_accion_borrar, $this -> ruta_form_borrar, true, '_self', array('class' => 'borrar'));
        
        $accionBorrar -> setConfirmMessage($this -> msg_confirmar_borrar);
        
        $grid->addRowAction($accionBorrar);
        $grid->addRowAction($accionEditar);
        
        $grid->addMassAction(new DeleteMassAction(true));
        
        $grid->setLimits(array(10,15,20,30,50));
        
        $grid -> isReadyForRedirect();
		
		return $grid;
	}
	
	public function verLibrosAction(){
		$opciones = $this -> getOpcionesPlantilla();
		
		$opciones['datos'] = $this -> getDatosAVisualizar();
		
		$render = null;
		
		if($this -> getRequest() -> isXmlHttpRequest()){
			$render = $this -> getPlantillaGrid();
		}
		else{ 
			$render = $this -> getPath() . $this -> plantilla_verDatos;
		}			
		
		return $opciones['datos'] -> getGridResponse($render, $opciones);
	}
	
	public function crearLibroAction($crearMasivo = null){
		if($this -> getRequest() -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$em = $this -> getEm();
		$peticion = $this -> getRequest();
		
		$opciones = $this -> getOpcionesPlantilla();
		$opciones['datos'] = $this -> getDatosAVisualizar();
		
		$opciones['titulo_ventana'] = $this -> titulo_crear;
		$opciones['path_form'] = $this -> generateUrl($this -> ruta_form_crear);
		$opciones['titulo_submit'] = $this -> titulo_submit_crear;
		
		$entidad = $this -> getNuevaInstancia($this -> entidad_clase);
		
		$opcionesFormulario['crearMasivo'] = 0;
		
		if($crearMasivo != null){
			$opcionesFormulario['crearMasivo'] = 1;
		}
		
		$opciones['form'] = $this -> createForm($this -> getFormulario('creador'), $entidad, $opcionesFormulario);
		
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
			
			if($opciones['form'] -> isValid()){
				$em -> persist($entidad);
				$em -> flush();				
				
				$crearMasivo = $opciones['form'] -> get('crearMasivo') -> getData();
				
				if($crearMasivo){
					$salida = $this -> redirect($this -> generateUrl($this -> ruta_form_crear, array('crearMasivo' => 1)));
				}
				else{
					$salida = $this -> irInicio();
				}
				
				$this -> setFlash($this -> flash_crear);
				
				return $salida;
			}
		}
		
		$ventanaModal = new VentanaModal(
				$this -> getPath(), 
				$opciones);
		
		return $ventanaModal -> renderVentanaModal($this);
	}
	
	public function editarLibroAction($isbn){		
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $isbn;		
		$em = $this -> getEm();
		
		$entidad = $em -> getRepository($this -> getNombreLogico() . ':' . $this -> entidad) -> find($idEntidad);
		
		if(!$entidad){
			return $this -> irInicio();
		}
		
		$opciones = $this -> getOpcionesPlantilla();
		$opciones['datos'] = $this -> getDatosAVisualizar();
			
		$opciones['path_cierre'] = $this -> generateUrl($this -> getInicio());
		$opciones['path_form'] = $this -> generateUrl($this -> ruta_form_editar, array('isbn' => $idEntidad));
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
			
		$ventanaModal = new VentanaModal(
				$this -> getPath(),
				$opciones);
			
		return $ventanaModal -> renderVentanaModal($this);
	}
	
	public function borrarLibroAction($isbn){
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $isbn;		
		$em = $this -> getEm();
			
		$entidad = $em -> getRepository($this -> getNombreLogico() . ':' . $this -> entidad) -> find($idEntidad);
			
		if(!$entidad){
			return $this -> irInicio();
		}
			
		$opciones = $this -> getOpcionesPlantilla();
		$opciones['datos'] = $this -> getDatosAVisualizar();
		
		$opciones['path_cierre'] = $this -> generateUrl($this -> getInicio());
		$opciones['path_form'] = $this -> generateUrl($this -> ruta_form_borrar, array('isbn' => $idEntidad));
		$opciones['titulo_ventana'] = $this -> titulo_borrar;
		$opciones['msg'] = $this -> msg_borrar;
		$opciones['titulo_form'] = $this -> titulo_form_borrar;
		$opciones['msg_confirmar'] = $this -> msg_confirmar_borrar;
		$opciones['titulo_submit'] = $this -> titulo_submit_borrar;
			
		$opciones['form'] = $this -> createForm($this -> getFormulario('visor'), $entidad);
		
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
		
			if($opciones['form'] -> isValid()){
				$em -> remove($entidad);
				$em -> flush();
		
				$this -> setFlash($this -> flash_borrar);
				return $this -> irInicio();
			}
		}
		
		$ventanaModal = new VentanaModal(
				$this -> getPath(),
				$opciones);
		
		return $ventanaModal -> renderVentanaModal($this);
	}
}
