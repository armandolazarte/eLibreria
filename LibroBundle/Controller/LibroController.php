<?php
namespace RGM\eLibreria\LibroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RGM\eLibreria\IndexBundle\Controller\ParseadorFichero;

class LibroController extends Asistente{
	private $bundle = 'librobundle';
	private $controller = 'libro';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this);

		// create a column
		$autores = new BlankColumn(array('id' => 'autores', 'title' => 'Autores', 'size' => '100', 'safe' => false));
		$estilos = new BlankColumn(array('id' => 'estilos', 'title' => 'Estilos', 'size' => '50', 'safe' => false));
		$stock = new BlankColumn(array('id' => 'stock', 'title' => 'Stock/Totales', 'size' => '50'));
		$pv = new BlankColumn(array('id' => 'p_venta', 'title' => 'Porcentaje de Venta', 'size' => '50'));
		$vEj = new BlankColumn(array('id' => 'ver_ejem', 'title' => 'Ver ejemplares', 'safe' => false));
		
		$grid->getGrid()->addColumn($autores);
		$grid->getGrid()->addColumn($estilos);
		$grid->getGrid()->addColumn($stock);
		$grid->getGrid()->addColumn($pv);
		$grid->getGrid()->addColumn($vEj);
		
		$controlador = $this;		
		$grid->getSource()->manipulateRow(function($row) use($controlador){
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
				$salida_estilos = '<ul class="estilos">';
				
				foreach($estilos as $e){
					$salida_estilos .= '<li>' . $e . '</li>';
				}
				
				$salida_estilos .= '</ul>';
			}
			
			$ruta_ver_ejemplares = $this->generateUrl($this->getParametro('ruta_ver_ejemplares'), array('isbn' => $entidad->getIsbn()));
			
			$salida_ver_Ejemplares = '<a onclick=\'window.open(this.href, "mywin","left=20,top=20,width=960,height=500,toolbar=1,resizable=0"); return false;\' href="'.$ruta_ver_ejemplares.'">Ver Ejemplares</a>';

			$row->setField('autores', $salida_autores);
			$row->setField('estilos', $salida_estilos);
			$row->setField('stock', $salida_ejemplares);
			$row->setField('ver_ejem', $salida_ver_Ejemplares);
			$row->setField('p_venta', ($entidad->getPorcentajeVenta() * 100) . '%' );
				
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
	
	public function verLibrosAction(Request $peticion){
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
	
	public function crearLibroAction($crearMasivo = null, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_crear');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$entidad = $this->getNuevaInstancia($this->getParametro('clase_entidad'));
		
		$opcionesFormulario['crearMasivo'] = 0;
		
		if($crearMasivo != null){
			$opcionesFormulario['crearMasivo'] = 1;
		}
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('ruta_form_crear'));
		$opcionesVM['titulo_submit'] = $this->getParametro('titulo_submit_crear');
		$opcionesVM['form'] = $this->createForm($this->getFormulario('creador'), $entidad, $opcionesFormulario);
	
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
				
			if($opcionesVM['form']->isValid()){
				$em->persist($entidad);
				$em->flush();
				
				$crearMasivo = $opcionesVM['form']->get('crearMasivo')->getData();
				
				if($crearMasivo){
					$salida = $this->redirect($this->generateUrl($this->getParametro('ruta_form_crear'), array('crearMasivo' => 1)));
				}
				else{
					$salida = $this->irInicio();
				}
	
				$this->setFlash($this->getParametro('flash_crear'));
				return $salida;
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
	public function editarLibroAction($isbn, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($isbn);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_editar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_editar'), array("isbn"=>$isbn));
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
	
	public function borrarLibroAction($isbn, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($isbn);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_borrar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_borrar'), array("isbn"=>$isbn));
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
	
	public function verEjemplaresLibroAction(Request $peticion, $isbn){
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($isbn);
		
		$opciones = array();
		
		if(!$entidad){
			$opciones['error'] = "Libro no encontrado";
		}
		else{
			$opciones['libro'] = $entidad;
		}
		
		return $this->render($this->getPlantilla('verEjemplares'), $opciones);
	}
	
	public function buscarLibroAjaxAction(Request $peticion){
		$res = array();
		$array_salida = array();

		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
	
			$isbn .= '%';
			
			$em = $this->getEm();
			$qb = $em->createQueryBuilder();
			$qb->select('l')
			->from('RGM\\eLibreria\LibroBundle\Entity\Libro', 'l')
			->add('where', $qb->expr()->like('l.isbn', '?1'))
			->setParameter(1, $isbn);
			
			$libros = $qb->getQuery()->getResult();
			
			$salida = array();
			
			foreach($libros as $l){
				$array_libro = array();
	
				$array_libro['isbn'] = $l->getISBN();
				$array_libro['titulo'] = $l->getTitulo();
				
				if($l->getEditorial()){
					$array_libro['editorial'] = $l->getEditorial()->getNombre();
				}
								
				$salida[] = $array_libro;
			}
			
			$array_salida['sugerencias'] = $salida;
		}

		$res = new Response(json_encode($array_salida));
		$res->headers->set('Content-Type', 'application/json');
		
		return $res;
	}
	
// 	public function importarLibroAction(Request $peticion){
// 		$opciones = $this->getArrayOpcionesVista();
// 		$em = $this->getEm();
// 		$parseador = new ParseadorFichero('/home/inma/Documentos/juridicos/juridico2.csv');
		
// 		$librosNuevos = $parseador->parsearFichero();
		
// 		$estiloJuridico = $em->getRepository($this->getEntidadLogico('Estilo'))->findBy(array(
// 				'denominacion' => 'juridico'
// 		));
		
// 		foreach($librosNuevos as $l){
// 			$libro = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($l['isbn']);
			
// 			if(!$libro){
// 				$libro = $this->getNuevaInstancia($this->getParametro('clase_entidad'));

// 				$libro->setIsbn($l['isbn']);
// 				$libro->setTitulo($l['titulo']);
				
// 				$libro->getEstilos()->add($estiloJuridico[0]);
								
// 				$em->persist($libro);
// 				$em->flush();
// 			}
// 		}
		
		
// 		return $this->render('RGMELibreriaIndexBundle::layout.html.twig', $opciones);
// 	} 
}
