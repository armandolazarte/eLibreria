<?php 
namespace RGM\eLibreria\FinanciacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Component\HttpFoundation\Request;

class PagosController extends Asistente{
	private $bundle = 'financiacionbundle';
	private $controller = 'pagos';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid($idGasto){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this, true, false);

		$grid->getGrid()->setDefaultOrder('fecha', 'desc');
		
		$tableAlias = $grid->getSource()->getTableAlias();
		
		$grid->getSource()->manipulateQuery(
				function ($query) use ($tableAlias, $idGasto)
				{
					$query->andWhere($tableAlias . '.gasto = ' . $idGasto);
				}
		);

		$grid->getGrid()->setSource($grid->getSource());
		
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
	
		return $opciones;
	}
	
	private function irInicioPago($idGasto){
		return $this->redirect($this->generateUrl($this->getParametro('inicio_pago'), array('idGasto' => $idGasto)));
	}
	
	public function verPagosAction($idGasto, Request $peticion){
		$render = null;
		$grid = $this->getGrid($idGasto);
		
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
	
	public function crearPagoAction($idGasto, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicioPago($idGasto);
		}
	
		$em = $this->getEm();

		$entidad_gasto = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad_gasto')))->find($idGasto);
		
		if(!$entidad_gasto){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_crear');
		$opciones['vm']['plantilla'] = $this->getPlantilla('vm_formularios');
	
		$entidad = $this->getNuevaInstancia($this->getParametro('clase_entidad'), array($entidad_gasto));
	
		$opcionesVM = array();
		$opcionesVM['path_form'] = $this->generateUrl($this->getParametro('ruta_form_crear'), array("idGasto"=>$idGasto));
		$opcionesVM['titulo_submit'] = $this->getParametro('titulo_submit_crear');
		$opcionesVM['form'] = $this->createForm($this->getFormulario('editor'), $entidad);
	
		if($peticion->getMethod() == "POST"){
			$opcionesVM['form']->bind($peticion);
				
			if($opcionesVM['form']->isValid()){
				$em->persist($entidad);
				$em->flush();
	
				$this->setFlash($this->getParametro('flash_crear'));
				return $this->irInicioPago($idGasto);
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid($idGasto);
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
	public function editarPagoAction($id, Request $peticion){
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
	
		$gasto = $entidad->getGasto();
		$gastoId = $gasto->getId();
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		if($peticion->isXmlHttpRequest()){
			return $this->irInicioPago($gastoId);
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
				return $this->irInicioPago($gastoId);
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid($gastoId);
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
	public function borrarPagoAction($id, Request $peticion){
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
	
		$gasto = $entidad->getGasto();
		$gastoId = $gasto->getId();
		
		if(!$entidad){
			return $this->irInicio();
		}
		
		if($peticion->isXmlHttpRequest()){
			return $this->irInicioPago($gastoId);
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
				return $this->irInicioPago($gastoId);
			}
		}
	
		$opcionesVM['form'] = $opcionesVM['form']->createView();
	
		$opciones['vm']['opciones'] = $opcionesVM;
	
		$grid = $this->getGrid($gastoId);
		$grid->setOpciones($opciones);
	
		return $grid->getRender($this->getPlantilla('principal'));
	}
	
}
?>