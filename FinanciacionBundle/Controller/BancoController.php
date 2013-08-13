<?php 
namespace RGM\eLibreria\FinanciacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;

class BancoController extends AsistenteController{
	
	private $seccion = 'Gestor de Finanzas';
	private $subseccion = 'Bancos';
	
	private $logicoBundle = 'RGMELibreriaFinanciacionBundle';
	private $ruta_inicio = 'rgm_e_libreria_financiacion_banco_homepage';
	
	private $entidad = 'Banco';
	private $entidad_clase = 'RGM\eLibreria\FinanciacionBundle\Entity\Banco';
	private $alias = 'b';
	
	private $nombreFormularios = array(
			'editor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\Banco\BancoType',
			'visor' => 'RGM\eLibreria\FinanciacionBundle\Form\Frontend\Banco\BancoVisorType'
	);
	
	private $ruta_form_crear = 'rgarcia_entrelineas_localizacion_crear';
	private $titulo_crear = 'Crear Banco';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Banco creado con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgarcia_entrelineas_localizacion_editar';
	private $titulo_editar = 'Editar Banco';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Banco editado con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgarcia_entrelineas_localizacion_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Banco';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el autor?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Banco borrado con exito';
	
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
	
	public function verBancosAction(){
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
	
	public function crearBancoAction(){
		
	}
	
	public function editarBancoAction($id){
		
	}
	
	public function borrarBancoAction($id){
		
	}
}
?>