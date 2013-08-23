<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

class AlbaranController extends AsistenteController{
	private $seccion = 'Gestor de Suministros';
	private $subseccion = 'Albaranes';

	private $repositorio_libro = 'RGMELibreriaLibroBundle:Libro';
	private $entidad_libro = 'RGM\eLibreria\LibroBundle\Entity\Libro';
	
	private $entidad_ejemplar = 'RGM\eLibreria\LibroBundle\Entity\Ejemplar';
	
	private $repositorio_editorial = 'RGMELibreriaLibroBundle:Editorial';
	private $entidad_editorial = 'RGM\eLibreria\LibroBundle\Entity\Editorial';
	
	private $logicoBundle = 'RGMELibreriaSuministroBundle';
	private $ruta_inicio = 'rgm_e_libreria_suministro_albaran_homepage';
	
	private $entidad = 'Albaran';
	private $entidad_clase = 'RGM\eLibreria\SuministroBundle\Entity\Albaran';
	private $alias = 'a';
	
	private $nombreFormularios = array(
			'creadorAlbaran' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\CrearAlbaranType',
			'editor' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\AlbaranType',
			'visor' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\AlbaranVisorType'
	);

	private $plantilla_ver_albaran = 'RGMELibreriaSuministroBundle:Albaran:verAlbaran.html.twig';
	private $plantilla_crear_albaran = 'RGMELibreriaSuministroBundle:Albaran:crearAlbaran.html.twig';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgm_e_libreria_suministro_albaran_editar';
	private $titulo_editar = 'Editar albaran';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Albaran editado con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgm_e_libreria_suministro_albaran_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar albaran';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el albaran?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Albaran borrado con exito';
	
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
				null,
				$this->getOpcionesGridAjax());
	}
	
	public function verAlbaranesAction(){
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
	
	public function crearAlbaranAction(){
		$peticion = $this->getRequest();
		$opciones = $this->getOpcionesPlantilla();
		$em = $this->getEm();
		
		$opciones['ruta_form'] = $this->generateUrl('rgm_e_libreria_suministro_albaran_crear');
		$opciones['salida'] = null;
		$opciones['path_ajax_autocompletar'] = $this->generateUrl('rgm_e_libreria_suministrobundle_albaran_buscar_referencia_ajax');
						
		$entidad = $this->getNuevaInstancia($this->entidad_clase);
		
		$opciones['form'] = $this->createForm($this->getFormulario('creadorAlbaran'), $entidad);
		
		if($peticion->getMethod() == "POST"){
			$opciones['form']->bind($peticion);
			
			if($opciones['form']->isValid()){
				$lineas = $opciones['form'] -> get('lineas') -> getData();
				
				//Persisto el albaran
				$em->persist($entidad);
				$em->flush();
				
				//Recorro todas las lineas del albaran
				foreach($lineas as $l){
					//Busco si existe el libro
					$libro = $em->getRepository($this->repositorio_libro)->find($l->getRef());
					
					if(!$libro){
						//Si el libro no existe: Crearlo y persistirlo
						
						$libro = $this->getNuevaInstancia($this->entidad_libro);
						
						$libro->setISBN($l->getRef());
						$libro->setTitulo($l->getTitulo());
						
						//Mirar si editorial existe, sino, crearla y vincularla al libro
						$editorial = $em->getRepository($this->repositorio_editorial)->findOneBy(array(
								'nombre' => $l->getEditorial()
						));
						
						if(!$editorial){
							$editorial = $this->getNuevaInstancia($this->entidad_editorial);
							
							$editorial->setNombre($l->getEditorial());
							
							$em->persist($editorial);
							$em->flush();
						}
						
						$libro->setEditorial($editorial);
						
						$em->persist($libro);
						$em->flush();
					}
				}
				
			}
		}
		
		$opciones['form'] = $opciones['form']->createView();
		
		return $this->render($this->plantilla_crear_albaran, $opciones);
	}
	
	public function buscarAjaxAction(){
		$isbn = $this->getRequest()->query->get('isbn');
				
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
			$array_libro['editorial'] = null;
			
			if($l->getEditorial()){
				$array_libro['editorial'] = $l->getEditorial()->getNombre();
			}
			
			$salida[] = $array_libro;
		}
		
		$array_salida['sugerencias'] = $salida;
		
		$res = new Response(json_encode($array_salida));
		
		return $res;
	}
	
	public function editarAlbaranAction($id){
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
	
	public function borrarAlbaranAction($id){
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
	
	public function verAlbaranAction(){
		return $this->render($this->plantilla_albaran, array());
	}
}
?>