<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use RGM\eLibreria\VentasBundle\Entity\SeleccionInforme;
use RGM\eLibreria\VentasBundle\Entity\ContenidoDistribuidora;

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
			$ventas = $repositorio->findVentasPorDistribuidorasFiltroFecha($seleccionInforme->getMes(), $seleccionInforme->getAnno());
			
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
	}}
?>