<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use RGM\eLibreria\LibroBundle\Entity\Ejemplar;
use RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran;
use RGM\eLibreria\LibroBundle\Entity\Libro;
use RGM\eLibreria\LibroBundle\Entity\Editorial;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Component\HttpFoundation\Request;

class ArticuloController extends Asistente{
	private $bundle = 'suministrobundle';
	private $controller = 'articulo';	
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid(){
		$infoEntidad = $this->getParametro('entidad');
		$grid = new GridController($this->getEntidadLogico($infoEntidad['repositorio']), $this);
		
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
	
	public function verArticulosAction(Request $peticion){
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

    public function crearArticuloAction(Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();
				
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_crear');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
		
		$infoEntidad = $this->getParametro('entidad');
		$entidad = $this->getNuevaInstancia($infoEntidad['clase']);
		
		$opcionesVM = array();		
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('ruta_form_crear'));
		$opcionesVM['titulo_submit'] = $this->getParametro('titulo_submit_crear');		
		$opcionesVM['form'] = $this->createForm($this->getFormulario('editor'), $entidad);
		
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
			
			if($opcionesVM['form']->isValid()){								
				$em->persist($entidad);
				$em->flush();
				
				$this->setFlash($this->getParametro('flash_crear'));
				return $this->irInicio();
			}
		}
		
		$opcionesVM['form'] = $opcionesVM['form']->createView();
		
		$opciones['vm']['opciones'] = $opcionesVM;
		
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
		
		return $grid->getRender($this->getPlantilla('principal'));
    }
    
    public function editarArticuloAction($id, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();
		
		$infoEntidad = $this->getParametro('entidad');
		$entidad = $em->getRepository($this->getEntidadLogico($infoEntidad['repositorio']))->find($id);
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_editar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
		
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_editar'), array("id"=>$id));
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
    
    public function borrarArticuloAction($id, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
		
		$em = $this->getEm();

		$infoEntidad = $this->getParametro('entidad');
		$entidad = $em->getRepository($this->getEntidadLogico($infoEntidad['repositorio']))->find($id);
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_borrar');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
		
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('grid_ruta_borrar'), array("id"=>$id));
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
	
	public function buscarArticuloAjaxAction(Request $peticion){
		$res = array();
		$array_salida = array();

		if($peticion->getMethod() == "POST"){
			$ref = $peticion->request->get('ref');
	
			$ref .= '%';
			
			$em = $this->getEm();
			$qb = $em->createQueryBuilder();
			$qb->select('a')
			->from('RGM\\eLibreria\SuministroBundle\Entity\Articulo', 'a')
			->add('where', $qb->expr()->like('a.ref', '?1'))
			->setParameter(1, $ref);
			
			$articulos = $qb->getQuery()->getResult();
			
			$salida = array();
			
			foreach($articulos as $a){
				$array_art = array();
	
				$array_art['ref'] = $a->getRef();
				$array_art['titulo'] = $a->getTitulo();
								
				$salida[] = $array_art;
			}
			
			$array_salida['sugerencias'] = $salida;
		}

		$res = new Response(json_encode($array_salida));
		$res->headers->set('Content-Type', 'application/json');
		
		return $res;
	}	
}
?>