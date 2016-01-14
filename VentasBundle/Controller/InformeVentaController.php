<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use RGM\eLibreria\VentasBundle\Entity\SeleccionInforme;
use RGM\eLibreria\VentasBundle\Entity\ContenidoDistribuidora;
use RGM\eLibreria\VentasBundle\Entity\OrdenVenta;
use RGM\eLibreria\VentasBundle\Entity\DispensadorItemsVenta;

class InformeVentaController extends Asistente{
	private $bundle = 'ventasbundle';
	private $controlador = 'informeventa';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function generarInformeAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array());
		$seleccionInforme = new SeleccionInforme();
		$em = $this->getEm();
		
		$formulario = $this->createForm($this->getFormulario('seleccionInforme'), $seleccionInforme);
		
		if($peticion->getMethod() == "POST"){
			$formulario->bind($peticion);
			
			$repositorio = $em->getRepository('RGMELibreriaVentasBundle:Venta');
			$ventas = $repositorio->findVentasPorFecha($seleccionInforme->getMes(), $seleccionInforme->getAnno());
			
			$distribuidoras = new ArrayCollection();
			$total = 0.0;
			$totalBase = 0.0;
			$totalIVA = 0.0;
			
			foreach ($ventas as $v){
				$items = $v->getItems();
				
				foreach ($items as $i){					
					$existencia = $i->getExistencia();
					$itemS = $existencia->getItemAlbaran();
					$alb = $itemS->getAlbaran();
					$contratoS = $alb->getContrato();
					$d = $contratoS->getDistribuidora();

					$total += $i->getPrecioVenta();
					$totalBase += $i->getPrecioVenta() / (1 + $existencia->getIVA());
					$totalIVA += $existencia->getIVA() * $i->getPrecioVenta() / (1 + $existencia->getIVA());
					
					if($distribuidoras->containsKey($d->getId())){
						$distribuidoras->get($d->getId())->addExistencia($existencia);
					}
					else{
						$con = new ContenidoDistribuidora($d);
						$con->addExistencia($existencia);
												
						$distribuidoras->set($d->getId(), $con);
					}					
				}
			}
			
			$opciones['resultadoBusqueda'] = $seleccionInforme->getMesString() . '/' . $seleccionInforme->getAnno();
			$opciones['resultados'] = $distribuidoras;
			$opciones['total'] = $total;
			$opciones['totalBase'] = $totalBase;
			$opciones['totalIVA'] = $totalIVA;
		}
		
		$opcionesFormulario['form'] = $formulario->createView();
		$opcionesFormulario['ruta_destino_form'] = $this->generateUrl($this->getInicio());
		$opcionesFormulario['titulo_submit'] = 'Generar Informe';
		
		$opciones['formulario'] = $opcionesFormulario;
		
		return $this->render($this->getPlantilla('principal'), $opciones);
	}
	
	public function generarInforme2Action(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array());
		$seleccionInforme = new SeleccionInforme();
		$em = $this->getEm();
		
		$formulario = $this->createForm($this->getFormulario('seleccionInforme'), $seleccionInforme);
		
		if($peticion->getMethod() == "POST"){
			$formulario->bind($peticion);

			$repositorio = $em->getRepository('RGMELibreriaVentasBundle:Venta');
			$ventas = $repositorio->findVentasPorFechaOrdenado($seleccionInforme->getMes(), $seleccionInforme->getAnno());
			
			$dispensadorOrdenVenta = new DispensadorOrdenVenta();
			$orden = $dispensadorOrdenVenta->getNumOrden($em, $seleccionInforme->getAnno(), $seleccionInforme->getMes());
			
			$existencias = array();
			
			$totalBaseImp = 0.0;
			$tiva1 = 0.04;
			$iva1 = 0.0;
			$tiva2 = 0.21;
			$iva2 = 0.0;
			$trec1 = 0.005;
			$rec1 = 0.0;
			$trec2 = 0.052;
			$rec2 = 0.0;
			$total = 0.0;
			$totalCaja = 0.0;
			$totalBanco = 0.0;
			
			foreach ($ventas as $v){
				$items = $v->getItems();
				$mPago = $v->getMetodoPago();
				
				foreach ($items as $i){
					$elemento = array();
					$existencia = $i->getExistencia();
					
					//Precio Venta
					//TipoIva
					//TipoRecargo
					//BImp = Precio Venta / (1 + (TipoIva/100))
					//IVA
					//Recargo
					//Metodo de Pago (1-Efec/2-Tarj)
					$precioVenta = $i->getPrecioVenta();
					$tipoIVA = $existencia->getIVA();
					$bImp = $precioVenta / (1 + $tipoIVA);
					$iva = $bImp * $tipoIVA;
					$tipoRecargo = $this->getRecargoPorIVA($tipoIVA);
					$rec = $bImp * $tipoRecargo;
					
					$totalBaseImp += $bImp;
					
					if($tipoIVA == $tiva1){
						$iva1 += $iva;
						$rec1 += $rec;
					}
					else{
						$iva2 += $iva;
						$rec2 += $rec;
					}
					
					//$precioVenta += $rec;
					
					if($mPago == 1){
						$totalCaja += $precioVenta;
					}
					else{
						$totalBanco += $precioVenta;
					}
					
					$total += $precioVenta;

					$elemento['nOrden'] = $orden++;
					$elemento['fecha'] = $v->getFecha();
					$elemento['distribuidora'] = $existencia->getNombreDistribuidora();
					$elemento['bImp'] = $bImp;
					$elemento['tIva'] = $tipoIVA;
					$elemento['Iva'] = $iva;
					$elemento['tRec'] = $tipoRecargo;
					$elemento['Rec'] = $rec;
					$elemento['mPago'] = $mPago;
					$elemento['total'] = $precioVenta;
					
					$existencias[] = $elemento;
				}
			}
			
			$opciones['TotalBaseImp'] = $totalBaseImp;
			$opciones['TipoIva1'] = $tiva1;
			$opciones['Iva1'] = $iva1;
			$opciones['TipoIva2'] = $tiva2;
			$opciones['Iva2'] = $iva2;
			$opciones['TipoRecargo1'] = $trec1;
			$opciones['Recargo1'] = $rec1;
			$opciones['TipoRecargo2'] = $trec2;
			$opciones['Recargo2'] = $rec2;
			$opciones['Total'] = $total;
			$opciones['TotalCaja'] = $totalCaja;
			$opciones['TotalBanco'] = $totalBanco;
			
			
			$opciones['resultadoBusqueda'] = $seleccionInforme->getMesString() . '/' . $seleccionInforme->getAnno();
			$opciones['resultados'] = $existencias;
		}
		
		$opcionesFormulario['form'] = $formulario->createView();
		$opcionesFormulario['ruta_destino_form'] = $this->generateUrl('rgm_e_libreria_ventas_informe2_index');
		$opcionesFormulario['titulo_submit'] = 'Generar Informe';
		
		$opciones['formulario'] = $opcionesFormulario;
		
		return $this->render($this->getPlantilla('principal2'), $opciones);
	}
	
	public function generarInformePorDIAAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array());
		$seleccionInforme = new SeleccionInforme();
		$em = $this->getEm();
		
		$formulario = $this->createForm($this->getFormulario('seleccionInformeDIA'), $seleccionInforme);
		
		if($peticion->getMethod() == "POST"){
			$formulario->bind($peticion);

			$repositorio = $em->getRepository('RGMELibreriaVentasBundle:Venta');
			$ventas = $repositorio->findVentasPorFechaOrdenadoDIA($seleccionInforme->getDia(), $seleccionInforme->getMes(), $seleccionInforme->getAnno());
						
			$existencias = array();
			
			$totalBaseImp = 0.0;
			$tiva1 = 0.04;
			$iva1 = 0.0;
			$tiva2 = 0.21;
			$iva2 = 0.0;
			$trec1 = 0.005;
			$rec1 = 0.0;
			$trec2 = 0.052;
			$rec2 = 0.0;
			$total = 0.0;
			$totalCaja = 0.0;
			$totalBanco = 0.0;
			
			foreach ($ventas as $v){
				$items = $v->getItems();
				$mPago = $v->getMetodoPago();
				
				foreach ($items as $i){
					$elemento = array();
					$existencia = $i->getExistencia();
					
					//Precio Venta
					//TipoIva
					//TipoRecargo
					//BImp = Precio Venta / (1 + (TipoIva/100))
					//IVA
					//Recargo
					//Metodo de Pago (1-Efec/2-Tarj)
					$precioVenta = $i->getPrecioVenta();
					$tipoIVA = $existencia->getIVA();
					$bImp = $precioVenta / (1 + $tipoIVA);
					$iva = $bImp * $tipoIVA;
					$tipoRecargo = $this->getRecargoPorIVA($tipoIVA);
					$rec = $bImp * $tipoRecargo;
					
					$totalBaseImp += $bImp;
					
					if($tipoIVA == $tiva1){
						$iva1 += $iva;
						$rec1 += $rec;
					}
					else{
						$iva2 += $iva;
						$rec2 += $rec;
					}
					
					//$precioVenta += $rec;
					
					if($mPago == 1){
						$totalCaja += $precioVenta;
					}
					else{
						$totalBanco += $precioVenta;
					}
					
					$total += $precioVenta;

					$elemento['fecha'] = $v->getFecha();
					$elemento['distribuidora'] = $existencia->getNombreDistribuidora();
					$elemento['bImp'] = $bImp;
					$elemento['tIva'] = $tipoIVA;
					$elemento['Iva'] = $iva;
					$elemento['tRec'] = $tipoRecargo;
					$elemento['Rec'] = $rec;
					$elemento['mPago'] = $mPago;
					$elemento['total'] = $precioVenta;
					
					$existencias[] = $elemento;
				}
			}
			
			$opciones['TotalBaseImp'] = $totalBaseImp;
			$opciones['TipoIva1'] = $tiva1;
			$opciones['Iva1'] = $iva1;
			$opciones['TipoIva2'] = $tiva2;
			$opciones['Iva2'] = $iva2;
			$opciones['TipoRecargo1'] = $trec1;
			$opciones['Recargo1'] = $rec1;
			$opciones['TipoRecargo2'] = $trec2;
			$opciones['Recargo2'] = $rec2;
			$opciones['Total'] = $total;
			$opciones['TotalCaja'] = $totalCaja;
			$opciones['TotalBanco'] = $totalBanco;
			
			
			$opciones['resultadoBusqueda'] = $seleccionInforme->getDia() . '/' . $seleccionInforme->getMesString() . '/' . $seleccionInforme->getAnno();
			$opciones['resultados'] = $existencias;
		}
		
		$opcionesFormulario['form'] = $formulario->createView();
		$opcionesFormulario['ruta_destino_form'] = $this->generateUrl('rgm_e_libreria_ventas_informe3_index');
		$opcionesFormulario['titulo_submit'] = 'Generar Informe';
		
		$opciones['formulario'] = $opcionesFormulario;
		
		return $this->render($this->getPlantilla('principal3'), $opciones);
	}
}
?>