<?php 
namespace RGM\eLibreria\ReservasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use Symfony\Component\HttpFoundation\Request;

class ReservaController extends AsistenteController{
	private $seccion = 'Gestor de Reservas';
	private $subseccion = 'Reservas';
	
	private $logicoBundle = 'RGMeLibreriaReservasBundle';
	private $ruta_inicio = 'rgm_e_libreria_reserva_reserva_index';
	
	private $entidad = 'Reserva';
	private $entidad_clase = 'RGM\eLibreria\ReservasBundle\Entity\Reserva';
	private $alias = 'r';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\ReservasBundle\Form\Frontend\Reserva\ReservaType',
			'visor' => 'RGM\eLibreria\ReservasBundle\Form\Frontend\Reserva\ReservaVisorType'
	);
	
	private $ruta_form_crear = 'rgm_e_libreria_reserva_reserva_crear';
	private $titulo_crear = 'Crear Reserva';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Reserva creada con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgm_e_libreria_reserva_reserva_editar';
	private $titulo_editar = 'Editar Reserva';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Reserva editada con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgm_e_libreria_reserva_reserva_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Reserva';
	private $msg_confirmar_borrar = '¿Realmente desea borrar la reserva?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Reserva borrada con exito';
	
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
	
	public function verReservasAction(Request $peticion){
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
	
	public function crearReservaAction(Request $peticion){
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
	
	public function editarReservaAction($id, Request $peticion){	
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
	
	public function borrarReservaAction($id, Request $peticion){
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