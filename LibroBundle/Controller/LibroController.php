<?php

namespace RGM\eLibreria\LibroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;
//use APY\DataGridBundle\Grid\Action\RowAction;

class LibroController extends AsistenteController
{
	private $seccion = 'Gestor de Libros';
	private $subseccion = 'Libros';
	
	private $logicoBundle = 'RGMELibreriaLibroBundle';
	private $ruta_inicio = 'rgarcia_entrelineas_libro_homepage';
	
	private $entidad = 'Libro';
	private $entidad_clase = 'RGM\eLibreria\LibroBundle\Entity\Libro';
	private $alias = 'l';
	
	private $nombreFormularios = array(
			'creador' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroCrearMasivoType',
			'editor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroType',
			'visor' => 'RGM\eLibreria\LibroBundle\Form\Frontend\Libro\LibroVisorType'
	);	
	
	private $ruta_form_crear = 'rgarcia_entrelineas_libro_crear';
	private $titulo_crear = 'Crear Libro';
	private $titulo_submit_crear = 'Crear';
	private $flash_crear = 'Libro creado con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgarcia_entrelineas_libro_editar';
	private $titulo_editar = 'Editar Libro';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Libro editado con exito';

	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgarcia_entrelineas_libro_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar Libro';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el libro?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Libro borrado con exito';
	
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

		// create a column
		$autores = new BlankColumn(array('id' => 'autores', 'title' => 'Autores', 'size' => '100', 'safe' => false));
		$estilos = new BlankColumn(array('id' => 'estilos', 'title' => 'Estilos', 'size' => '50', 'safe' => false));
		$stock = new BlankColumn(array('id' => 'stock', 'title' => 'Stock/Totales', 'size' => '50'));
		$pv = new BlankColumn(array('id' => 'p_venta', 'title' => 'Porcentaje de Venta', 'size' => '50'));
		
		$grid->getGrid()->addColumn($autores);
		$grid->getGrid()->addColumn($estilos);
		$grid->getGrid()->addColumn($stock);
		$grid->getGrid()->addColumn($pv);
		
		$controlador = $this;
		
		$grid->getSource()->manipulateRow(
				function ($row) use ($controlador)
				{
					$entidad = $row->getEntity();

					$autores = $entidad->getAutores();
					$estilos = $entidad->getEstilos();
					$ejemplares = $entidad->getEjemplares();
					
					$salida_estilos = '-';
					$salida_autores = '-';
					$salida_ejemplares = $entidad->getStock() . '/' . $entidad->getTotalEjemplares();
					$salida_porcentaje = '-';
					
					if(!$autores->isEmpty()){
						$salida_autores = '<ul class="autores">';
						
						foreach($autores as $a){
							$salida_autores .= '<li>' . $a . '</li>';
						}
						
						$salida_autores .= '</ul>';
					}
					
					if(!$estilos->isEmpty()){
						$salida_estilos = '<ul class="autores">';
						
						foreach($estilos as $e){
							$salida_estilos .= '<li>' . $e . '</li>';
						}
						
						$salida_estilos .= '</ul>';
					}

					$row->setField('autores', $salida_autores);
					$row->setField('estilos', $salida_estilos);
					$row->setField('stock', $salida_ejemplares);
					$row->setField('p_venta', ($entidad->getPorcentajeVenta() * 100) . '%' );
						
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
	
	public function verLibrosAction(){
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
	
	public function crearLibroAction($crearMasivo = null){
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
		
		$grid = $this -> getGrid();
		$grid -> setOpciones($opciones);
		
		return $grid -> getRenderVentanaModal();
	}
	
	public function editarLibroAction($isbn){		
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $isbn;		
		$em = $this -> getEm();
		
		$entidad = $em -> getRepository($this -> getNombreEntidad()) -> find($idEntidad);
		
		if(!$entidad){
			return $this -> irInicio();
		}
		
		$opciones = $this -> getOpcionesVista();
			
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_editar, array('isbn' => $idEntidad));
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
	
	public function borrarLibroAction($isbn){
		$peticion = $this -> getRequest();
		if($peticion -> isXmlHttpRequest()){
			return $this -> irInicio();
		}
		
		$idEntidad = $isbn;		
		$em = $this -> getEm();
			
		$entidad = $em -> getRepository($this -> getNombreEntidad()) -> find($idEntidad);
			
		if(!$entidad){
			return $this -> irInicio();
		}
			
		$opciones = $this -> getOpcionesVista();
		
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_borrar, array('isbn' => $idEntidad));
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
