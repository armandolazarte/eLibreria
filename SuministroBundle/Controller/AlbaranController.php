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

class AlbaranController extends Asistente{
	private $bundle = 'suministrobundle';
	private $controller = 'albaran';	
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this);
		
		$columnaContrato = new BlankColumn(array('id' => 'contrato', 'title' => 'Contrato'));
		$columnaTotal = new BlankColumn(array('id' => 'total', 'title' => 'Total'));
		$columnaVer = new BlankColumn(array('id' => 'ver', 'title' => 'Ver Albaran', 'safe' => false));
		
		$grid->getGrid()->addColumn($columnaContrato);
		$grid->getGrid()->addColumn($columnaTotal);
		$grid->getGrid()->addColumn($columnaVer);
				
		$grid->getSource()->manipulateRow(function($row){
			$entidad = $row->getEntity();
				
			$enlaceVer = '<a onclick=\'window.open(this.href, "mywin","left=20,top=20,width=960,height=500,toolbar=1,resizable=0"); return false;\' href="' . $this->generateUrl('rgm_e_libreria_suministro_albaran_ver', array('id' => $entidad->getId())) . '">Ver Albaran</a>';
				
			$row->setField('contrato', $entidad->getContrato());
			$row->setField('ver', $enlaceVer);
				
			if(!$entidad->getTotal()){
				$this->calcularValorAlbaran($entidad);
			}
			$row->setField('total', round($entidad->getTotal(), 2) . '€');
				
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
	
	public function verAlbaranesAction(Request $peticion){
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
	
	public function importarAction(){
		$peticion = $this->getRequest();
		$opciones = $this->getOpcionesPlantilla();
		
		$importar = $this->getNuevaInstancia($this->entidad_importar_albaran);
		
		$opciones['formulario'] = $this->createForm($this->getFormulario('importar'), $importar);
		
		$albaranesSinCrear = array();
		$numEjemplares = 0;
		
		if($peticion->getMethod() == "POST"){
			$opciones['formulario']->bind($peticion);
			
			if($opciones['formulario']->isValid()){
				$fichero = $this->parsearFichero($importar->getFichero());
				$em = $this->getEm();
				
				foreach($fichero as $linea){					
					//Buscar/crear Libro
					$libro = $em->getRepository('RGMELibreriaLibroBundle:Libro')->find($linea['isbn']);
					
					if(!$libro){
						$libro = new Libro();
						
						$libro->setIsbn($linea['isbn']);
						$libro->setTitulo($linea['titulo']);
						
						$editorial = $em->getRepository('RGMELibreriaLibroBundle:Editorial')->findOneBy(array(
								"nombre" => $linea['editorial']
						));
						
						if(!$editorial){
							$editorial = new Editorial();
							
							$editorial->setNombre($linea['editorial']);
							
							$em->persist($editorial);
						}
						
						$libro->setEditorial($editorial);
						
						$em->persist($libro);
						$em->flush();
					}
				}
				
				foreach($fichero as $linea){
					$libro = $em->getRepository('RGMELibreriaLibroBundle:Libro')->find($linea['isbn']);
						
					$albaran = $em->getRepository('RGMELibreriaSuministroBundle:Albaran')->find($linea['albaran']);
					
					if($albaran){
						$numEjemplar = $linea['num1'] + $linea['num2'];
						
						for($i = 0; $i < $numEjemplar ; $i++){
							$ejemplar = new Ejemplar($libro);
							
							$ejemplar->setPrecio((float)preg_replace('/,/', '.', $linea['precio']));
							$ejemplar->setIVA((float)preg_replace('/,/', '.', $linea['iva']));
							
							$em->persist($ejemplar);
							
							$itemAlbaran = new ItemAlbaran();
							
							$itemAlbaran->setAlbaran($albaran);
							$itemAlbaran->setEjemplar($ejemplar);
							$itemAlbaran->setDescuento((float)preg_replace('/,/', '.', $linea['desc']));
							
							$em->persist($itemAlbaran);
							
							$numEjemplares++;
						}
					}
					else{
						$albaranesSinCrear[] = $linea['albaran'];
					}
				}
				
				if(count($albaranesSinCrear) == 0){
					$em->flush();
				}
			}
		}
		
		$opciones['albaranesSinCrear'] = $albaranesSinCrear;
		$opciones['numEjemplaresCreados'] = $numEjemplares;
		
		$opciones['formulario'] = $opciones['formulario']->createView();
		
		return $this->render($this->plantilla_importar_albaran, $opciones);
	}	
	
	public function borrarAlbaranAction($id, Request $peticion){
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
	
		$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
	
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
				foreach($entidad->getItems() as $item){
					$em->remove($item->getElemento());
				}
				
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
	
	public function verAlbaranAction(Request $peticion, $id){
		$em = $this->getEm();
		
		$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
		$opciones = array();
		
		$opciones['error'] = null;
		
		if(!$albaran){
			$opciones['error'] = 'Albaran no encontrado';
		}
		else{
			$calculoAlbaran = $this->calcularValorAlbaran($albaran);
			
			$opciones['albaran'] = $albaran;
			
			$opciones['bases'] = $calculoAlbaran['bases'];
				
			$opciones['bImp'] = $calculoAlbaran['bImp'];
			$opciones['bIva'] = $calculoAlbaran['bIva'];
			$opciones['bRec'] = $calculoAlbaran['bRec'];
			$opciones['bTotal'] = $calculoAlbaran['bTotal'];
				
			$opciones['lineas'] = $calculoAlbaran['lineas'];
			$opciones['numPaginas'] = $calculoAlbaran['numPaginas'];
		}
		
		return $this->render($this->getPlantilla('ver'), $opciones);
	}
	
	private function calcularValorAlbaran($albaran){
		$salida = array();
		$em = $this->getEm();
			
		$infoRecargo = $this->getParametro('recargo');
		$recargos = $em->getRepository($infoRecargo['repositorio'])->findAll();
			
		$bases = new ArrayCollection();
			
		foreach($recargos as $recargo){
			$i = array();
		
			$i['rec'] = $recargo->getRecargo();
			$i['iva'] = $recargo->getIva();
		
			$i['baseImp'] = 0;
			$i['ivaImp'] = 0;
			$i['recImp'] = 0;
		
			$bases->set((string) $recargo->getIva(), $i);
		}
			
		$lineasAlbaran = new ArrayCollection();
		$items = $albaran->getItems();
		
		foreach($items as $item){
			$existencia = $item->getExistencia();
			$referencia = $existencia->getReferencia();
		
			if($lineasAlbaran->containsKey($referencia)){
				$linea = $lineasAlbaran->get($referencia);
				$linea['numEjemplares'] += 1;
				$linea['vendidos'] += $existencia->getVendido();
					
				$lineasAlbaran->set($referencia, $linea);
			}
			else{
				$objeto = $existencia->getObjetoVinculado();
				$linea['ref'] = $referencia;
				$linea['titulo'] = $objeto->getTitulo();
				$linea['iva'] = $existencia->getIVA();
				$linea['precio'] = $existencia->getPrecio();
				$linea['numEjemplares'] = 1;
				$linea['vendidos'] = 0 + $existencia->getVendido();
				$linea['desc'] = $item->getDescuento();
					
				$lineasAlbaran->set($referencia, $linea);
			}
		
			$linea = $lineasAlbaran->get($referencia);
		
			$iva = $linea['iva'];
			$base = $bases->get((string) $iva);
			
			$baseImponible = $linea['precio'] * (1 - $linea['desc']);
		
			$base['baseImp'] += $baseImponible;
			$base['ivaImp'] += $baseImponible * $iva;
			$base['recImp'] += $baseImponible * $base['rec'];
		
			$bases->set((string) $iva, $base);
		}
			
		$bImp = 0;
		$bIva = 0;
		$bRec = 0;
		$bTotal = 0;
			
		foreach($bases as $base){
			$bImp += round($base['baseImp'],2);
			$bIva += round($base['ivaImp'],2);
			$bRec += round($base['recImp'],2);
		}
			
		$bTotal = $bImp + $bIva + $bRec;

		$salida['bases'] = $bases;
		
		$salida['bImp'] = $bImp;
		$salida['bIva'] = $bIva;
		$salida['bRec'] = $bRec;
		
		$albaran->setTotal($bTotal);
		$salida['bTotal'] = $bTotal;
		
		$em->persist($albaran);
		$em->flush();
		
		$salida['lineas'] = $lineasAlbaran->getValues();
		
		$infoImpresion = $this->getParametro('impresion');
		$salida['numPaginas'] = ceil(count($lineasAlbaran) / $infoImpresion['numElementosPagina']);
						
		return $salida;
	}
}
?>