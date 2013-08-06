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
			'editor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroType',
			'visor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroVisorType'
	);	
	
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
        
        $accionEditar = new RowAction('Editar', 'rgarcia_entrelineas_libro_editar', false, '_self', array('class' => 'editar'));
        $accionBorrar = new RowAction('Borrar', 'rgarcia_entrelineas_libro_borrar', true, '_self', array('class' => 'borrar'));
        
        $accionBorrar -> setConfirmMessage('¿Realmente quiere borrar el puesto seleccionado?');
        
        $grid->addRowAction($accionBorrar);
        $grid->addRowAction($accionEditar);
        
        $grid->addMassAction(new DeleteMassAction(true));
        
        $grid->setLimits(array(10,15,20,30,50));

        $this -> get('logger') -> info('Lanzando isReadyForRedirect');
        
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
		
		$opciones['titulo_ventana'] = 'Crear un libro';
		$opciones['path_form'] = $this -> generateUrl('rgarcia_entrelineas_libro_crear');
		$opciones['titulo_submit'] = 'Crear';
		
		$entidad = $this -> getNuevaInstancia($this -> entidad_clase);
		
		$opcionesFormulario['crearMasivo'] = 0;
		
		if($crearMasivo != null){
			$opcionesFormulario['crearMasivo'] = 1;
		}
		
		$opciones['form'] = $this -> createForm($this -> getFormulario('editor'), $entidad, $opcionesFormulario);
		
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
			
			if($opciones['form'] -> isValid()){
				$em -> persist($entidad);
				$em -> flush();				
				
				$crearMasivo = $opciones['form'] -> get('crearMasivo') -> getData();
				
				if($crearMasivo){
					$salida = $this -> redirect($this -> generateUrl('rgarcia_entrelineas_libro_crear', array('crearMasivo' => 1)));
				}
				else{
					$salida = $this -> irInicio();
				}
				
				$this -> setFlash('Libro creado con exito');
				
				return $salida;
			}
		}
		
		$ventanaModal = new VentanaModal(
				$this -> getPath(), 
				$opciones);
		
		return $ventanaModal -> renderVentanaModal($this);
	}
	
	public function editarLibroAction($isbn){
		
	}
	
	public function borrarLibroAction($isbn){
		
	}
}
