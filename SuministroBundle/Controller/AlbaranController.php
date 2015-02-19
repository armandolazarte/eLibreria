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
		
		$grid->getGrid()->getColumn('id')->setOrder('desc');
		
		$columnaContrato = new BlankColumn(array('id' => 'contrato', 'title' => 'Contrato'));
		$columnaTotal = new BlankColumn(array('id' => 'total', 'title' => 'Total'));
		$columnaVerAB = new BlankColumn(array('id' => 'verAB', 'title' => 'Ver Abonos', 'safe' => false));
		$columnaVer = new BlankColumn(array('id' => 'ver', 'title' => 'Ver Albaran', 'safe' => false));
		$columnaLiquid = new BlankColumn(array('id' => 'liquidacion', 'title' => 'Liquidación', 'safe' => false));
		
		$grid->getGrid()->addColumn($columnaContrato);
		$grid->getGrid()->addColumn($columnaTotal);
		$grid->getGrid()->addColumn($columnaVerAB);
		$grid->getGrid()->addColumn($columnaVer);
		$grid->getGrid()->addColumn($columnaLiquid);
				
		$grid->getSource()->manipulateRow(function($row){
			$entidad = $row->getEntity();

			$enlaceVerAB = '<a onclick=\'window.open(this.href, "mywin","left=20,top=20,width=960,height=500,toolbar=1,resizable=0"); return false;\' href="' . $this->generateUrl('rgm_e_libreria_suministro_albaran_verAB', array('id' => $entidad->getId())) . '">Ver Abonos</a>';
			$enlaceVer = '<a onclick=\'window.open(this.href, "mywin","left=20,top=20,width=960,height=500,toolbar=1,resizable=0"); return false;\' href="' . $this->generateUrl('rgm_e_libreria_suministro_albaran_ver', array('id' => $entidad->getId())) . '">Ver Albaran</a>';
			
			if($entidad->isLiquidado()){
				$estadoLiq = 'liquidado';
				$tituloLiq = 'Deshacer liquidación';
			}
			else{
				$estadoLiq = 'noliquidado';
				$tituloLiq = 'Liquidar';
			}
			
			$enlaceLiq = '<a href="' . $this->generateUrl('rgm_e_libreria_suministro_albaran_liquidarTotal', array('id' => $entidad->getId())) . '">'.$tituloLiq.'</a>';
				
			$row->setField('contrato', $entidad->getContrato());
			$row->setField('verAB', $enlaceVerAB);
			$row->setField('ver', $enlaceVer);
			$row->setField('liquidacion', $enlaceLiq);
				
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
					$em->remove($item->getExistencia());
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
			$calculador = new CalculoAlbaran($albaran, null);
			$calculoAlbaran = $this->calcularValorAlbaran($albaran);
			
			$opciones['albaran'] = $albaran;
			
			$opciones['bases'] = $calculoAlbaran['bases'];
				
			$opciones['bImp'] = $calculoAlbaran['bImp'];
			$opciones['bIva'] = $calculoAlbaran['bIva'];
			$opciones['bRec'] = $calculoAlbaran['bRec'];
			$opciones['bTotal'] = $calculoAlbaran['bTotal'];
			$opciones['vTotal'] = $calculoAlbaran['totalVendido'];
				
			$opciones['numPaginas'] = $calculoAlbaran['numPaginas'];
			
			$lineas = $calculoAlbaran['lineas'];			
			$mixLineas = array();
			
			foreach($lineas as $linea){
				//var_dump($linea);
				$mixLineas[] = $linea;
				
				if(array_key_exists('loc', $linea)){
					//var_dump($linea['loc']);
					foreach($linea['loc'] as $loc){
						$mixLineas[] = $loc;
					}
				}
			}
			
			$opciones['lineas'] = $mixLineas;
		}
		
		return $this->render($this->getPlantilla('ver'), $opciones);
	}
	
	public function verAbonoAction(Request $peticion, $id){
		$em = $this->getEm();
				
		$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
		$opciones = array();
			
		$infoRecargo = $this->getParametro('recargo');
		$recargos = $em->getRepository($infoRecargo['repositorio'])->findAll();
			
		$bases = new ArrayCollection();
			
		foreach($recargos as $recargo){
			$i = array();
		
			$i['rec'] = $recargo->getRecargo();
			$i['iva'] = $recargo->getIva();
		
			$i['baseImp'] = 0;
		
			$bases->set((string) $recargo->getIva(), $i);
		}
		
		$opciones['error'] = null;
		$lineas = array();
		$lineasBase = array();
		$totalBase = 0;
		$totalIVA = 0;
		$totalREC = 0;
		
		if(!$albaran){
			$opciones['error'] = 'Albaran no encontrado';
		}
		else{
			$opciones['albaran'] = $albaran;
			
			foreach($albaran->getItems() as $item){
				$existencia = $item->getExistencia();
				
				if(!$existencia->isVendido() && !$existencia->isAdquirido()){
					$tipo = $existencia->getTipo();
					$descuento = $item->getDescuento();
					$precioBase = $existencia->getPrecio();
					$iva = $existencia->getIva();
					$importe = $precioBase * (1 - $descuento);
										
					$localizacion = $existencia->getLocalizacion();
					if($localizacion){
						$localizacion = $localizacion->getDenominacion();
					}
					else{
						$localizacion = 'No se sabe';
					}

					$objeto = $existencia->getObjetoVinculado();
					$referencia = $existencia->getReferencia();
					$titulo = $objeto->getTitulo();

					$bloqueIVA = $bases->get((string) $iva);
					
					if($bloqueIVA){
						$bloqueIVA['baseImp'] += $importe;
						$bases->set((string) $iva, $bloqueIVA);
					}
					
					$linea = array();
					$linea['referencia'] = $referencia;
					$linea['titulo'] = $titulo;
					$linea['localizacion'] = $localizacion;
					$linea['precio'] = $precioBase;
					$linea['iva'] = $iva;
					$linea['descuento'] = $descuento;
					$linea['importe'] = $importe;
					
					$lineas[] = $linea;
				}
			}
			
			foreach($bases as $base){
				$baseImpTotal = $base['baseImp'];
				$iva = $base['iva'];
				$rec = $base['rec'];
					
				$ImpIVA = $baseImpTotal * $iva;
				$ImpRec = $baseImpTotal * $rec;
					
				$lineaBase = array();
				$lineaBase['base'] = $baseImpTotal;
				$totalBase += $baseImpTotal;
				$lineaBase['iva'] = $iva;
				$lineaBase['impIva'] = $ImpIVA;
				$totalIVA += $ImpIVA;
				$lineaBase['rec'] = $rec;
				$lineaBase['impRec'] = $ImpRec;
				$totalREC += $ImpRec;
					
				$lineasBase[] = $lineaBase;
			}
		}
		
		$numLineas = count($lineas);
		
		$numElementosPorPagina = 46;
		
		$opciones['numPaginas'] = ceil($numLineas / $numElementosPorPagina);		
		$opciones['numElementosPorPagina'] = $numElementosPorPagina;
		
		$opciones['lineas'] = $lineas;
		$opciones['lineasBase'] = $lineasBase;
		
		$opciones['bImp'] = $totalBase;
		$opciones['bIva'] = $totalIVA;
		$opciones['bRec'] = $totalREC;
		
		$opciones['bTotal'] = $totalBase + $totalIVA + $totalREC;

		return $this->render($this->getPlantilla('verAB'), $opciones);
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
		$lineasExtra = 0;
		
		foreach($items as $item){
			$existencia = $item->getExistencia();
			$referencia = $existencia->getReferencia();
			//var_dump($existencia);
			//var_dump($existencia->isVendido());
		
			if($lineasAlbaran->containsKey($referencia)){
				$linea = $lineasAlbaran->get($referencia);
				$linea['numEjemplares'] += 1;
				$linea['vendidos'] += $existencia->getVendido();
				
				if($existencia->isVendido()){
					$linea['totalVendido'] += $existencia->getPrecio() * (1 - $item->getDescuento());
				}
				$loc = $existencia->getLocalizacion();
				
				if($loc && !$existencia->isVendido()){
					//var_dump($existencia->isVendido());
					
					if(array_key_exists('loc', $linea)){
						$linea['loc'][] = $loc->getDenominacion();
						$lineasExtra++;
					}
					else{
						$linea['loc'] = array($loc->getDenominacion());
						$lineasExtra++;
					}
				}				
					
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
				$linea['totalVendido'] = 0;
				
				if($existencia->isVendido()){
					$linea['totalVendido'] += $existencia->getPrecio() * (1 - $item->getDescuento());
				}
				
				$linea['desc'] = $item->getDescuento();
				$loc = $existencia->getLocalizacion();
				
				if($loc && !$existencia->isVendido()){
					//var_dump($existencia->isVendido());
					$linea['loc'] = array($loc->getDenominacion());
					$lineasExtra++;
				}
					
				$lineasAlbaran->set($referencia, $linea);
			}
		}
		
		$totalVendido = 0;

		foreach($lineasAlbaran as $linea){
			$iva = $linea['iva'];
			$base = $bases->get((string) $iva);
			
			$totalVendido += $linea['totalVendido'];
			
			$precio = $linea['precio'];
			$numEjem = $linea['numEjemplares'];
			$desc = round(1 - $linea['desc'],2);
			
			$baseImponible = round($precio * $desc,2) * $numEjem;
			
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
			$bImp += $base['baseImp'];
			$bIva += $base['ivaImp'];
			$bRec += $base['recImp'];
		}
			
		$bTotal = $bImp + $bIva + $bRec;

		$salida['bases'] = $bases;
		
		$salida['bImp'] = $bImp;
		$salida['bIva'] = $bIva;
		$salida['bRec'] = $bRec;
		
		$albaran->setTotal($bTotal);
		$salida['bTotal'] = $bTotal;
		
		$salida['totalVendido'] = $totalVendido;
		
		$em->persist($albaran);
		$em->flush();
		
		$salida['lineas'] = $lineasAlbaran->getValues();
		$salida['lineasExtra'] = $lineasExtra;
		
		$infoImpresion = $this->getParametro('impresion');
		$salida['numPaginas'] = ceil( (count($lineasAlbaran) + $lineasExtra) / $infoImpresion['numElementosPagina']);
						
		return $salida;
	}
	
	public function liquidacionTotalAction(Request $peticion, $id){
		$em = $this->getEm();
				
		$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
		
		if($albaran->isLiquidado()){
			foreach($albaran->getItems() as $item){
				$existencia = $item->getExistencia();
				
				if(!$existencia->isVendido() && !$existencia->isAdquirido() && !$existencia->isVigente()){
					$existencia->setVigente(true);
					$em->persist($existencia);
				}
			}
			
			$albaran->setLiquidado(false);
			$em->persist($albaran);
			
			$mensajeFlash = 'Albaran recuperado.';
		}
		else{
			foreach($albaran->getItems() as $item){
				$existencia = $item->getExistencia();
				
				if(!$existencia->isVendido() && !$existencia->isAdquirido() && $existencia->isVigente()){
					$existencia->setVigente(false);
					$em->persist($existencia);
				}
			}
			
			$albaran->setLiquidado(true);
			$em->persist($albaran);
			
			$mensajeFlash = 'Albaran liquidado.';
		}
				
		$this->setFlash($mensajeFlash);
		$em->flush();
		return $this->irInicio();
	}
}
?>