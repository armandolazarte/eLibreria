<?php 
namespace RGM\eLibreria\LibroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;

class EditorialController extends AsistenteController{
	
	private $seccion = 'Gestor de Libros';
	private $subseccion = 'Editoriales';
	
	private $entidad = 'Editorial';
	private $entidad_clase = 'RGM\eLibreria\LibroBundle\Entity\Editorial';
	private $alias = 'e';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Editorial\EditorialType',
			'visor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Editorial\EditorialVisorType'
	);
	
	private $ruta_form_crear = 'rgarcia_entrelineas_editorial_crear';
	private $titulo_crear = 'Crear Editorial';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Editorial creada con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgarcia_entrelineas_editorial_editar';
	private $titulo_editar = 'Editar Editorial';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Editorial editada con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgarcia_entrelineas_editorial_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Editorial';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el autor?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Editorial borrada con exito';
	
	public function __construct(){
		parent::__construct(
				'rgarcia_entrelineas_editorial_homepage', 
				'RGMELibreriaLibroBundle', 
				'Libro:', 
				$this->seccion,
				$this->subseccion);
		
		$this -> setEntidad($this->entidad);		
		$this -> setFormularios($this -> nombreFormularios);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getNombreEntidad(), $this);
			
		return $grid;
	}
	
	private function getOpcionesGridAjax(){
		$res = array();
	
		$res['grid_boton_editar'] = $this -> grid_boton_editar;
		$res['grid_ruta_editar'] = $this -> grid_ruta_editar;
		$res['grid_boton_borrar'] = $this -> grid_boton_borrar;
		$res['grid_ruta_borrar'] = $this -> grid_ruta_borrar;
		$res['grid_confirmar_borrar'] = $this -> msg_confirmar_borrar;
		$res['grid_limites'] = array(6,10,15,30,60,100);
	
		return $res;
	}
	
	private function getOpcionesVista(){
		$res = $this -> getOpcionesGridAjax();
		$opcionesPlantilla = $this -> getOpcionesPlantilla();
	
		foreach($opcionesPlantilla as $clave => $valor){
			$res[$clave] = $valor;
		}
	
		$res['titulo_seccion'] = $this -> seccion;
		$res['titulo_subseccion'] = $this -> subseccion;
		$res['ruta_crear'] = $this -> ruta_form_crear;
	
		return $res;
	}
	
	public function verEditorialAction(){
		$peticion = $this -> getRequest();
		$render = null;
	
		$grid = $this -> getGrid();
	
		if($peticion -> isXmlHttpRequest()){
			$grid -> setOpciones($this -> getOpcionesGridAjax());
			$render = $grid -> getRenderAjax();
		}
		else{
			$grid -> setOpciones($this -> getOpcionesVista());
			$render = $grid -> getRender();
		}
	
		return $render;
	}
	
	public function crearEditorialAction(){
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$em = $this -> getEm();
		
		$opciones = $this -> getOpcionesVista();
		
		$opciones['titulo_ventana'] = $this -> titulo_crear;
		$opciones['path_form'] = $this -> generateUrl($this -> ruta_form_crear);
		$opciones['titulo_submit'] = $this -> titulo_submit_crear;
		
		$entidad = $this -> getNuevaInstancia($this -> entidad_clase);
		
		$opciones['form'] = $this -> createForm($this -> getFormulario('editor'), $entidad);
		
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
			
			if($opciones['form'] -> isValid()){
				$em -> persist($entidad);
				$em -> flush();				
				
				$this -> setFlash($this -> flash_crear);				
				return $this -> irInicio();
			}
		}
		
		$grid = $this -> getGrid();
		$grid -> setOpciones($opciones);
		
		return $grid -> getRenderVentanaModal();
	}
	
	public function editarEditorialAction($id){		
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $id;		
		$em = $this -> getEm();
		
		$entidad = $em -> getRepository($this -> getNombreEntidad()) -> find($idEntidad);
		
		if(!$entidad){
			return $this -> irInicio();
		}
		
		$opciones = $this -> getOpcionesVista();
			
		$opciones['path_cierre'] = $this -> generateUrl($this -> getInicio());
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_editar, array('id' => $idEntidad));
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
	
	public function borrarEditorialAction($id){
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $id;		
		$em = $this -> getEm();
			
		$entidad = $em -> getRepository($this -> getNombreEntidad()) -> find($idEntidad);
			
		if(!$entidad){
			return $this -> irInicio();
		}
			
		$opciones = $this -> getOpcionesVista();
		
		$opciones['path_cierre'] = $this -> generateUrl($this -> getInicio());
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_borrar, array('id' => $idEntidad));
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
		
		$grid = $this -> getGrid();
		$grid -> setOpciones($opciones);
		
		return $grid -> getRenderVentanaModal();
	}
}
?>