<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use Doctrine\ORM\EntityManager;
use RGM\eLibreria\VentasBundle\Entity\OrdenVenta;

class DispensadorOrdenVenta{
	public function getNumOrden(EntityManager $em, $anno, $mes){
		$repositorio = $em->getRepository('RGMELibreriaVentasBundle:OrdenVenta');
		$orden = $repositorio->findOneBy(array('anno' => $anno, 'mes' => $mes));
		$res = null;
		
		if($orden){
			$res = $orden->getNumOrden();
		}
		else{
			if($mes > 1){
				$ordenMesAnterior = $this->getNumOrden($em, $anno, $mes - 1);
				
				$repositorioVenta = $em->getRepository('RGMELibreriaVentasBundle:Venta');
				
				$ventasMesAnterior = $repositorioVenta->findExistenciasVendidasPorFecha($mes - 1, $anno);
				
				$res = $ventasMesAnterior + $ordenMesAnterior;
			}
			else{
				$res = 1;
			}
			
			$ordenMesActual = new OrdenVenta();
			$ordenMesActual->setAnno($anno);
			$ordenMesActual->setMes($mes);
			$ordenMesActual->setNumOrden($res);
			
			$hoy = new \DateTime();
			$fecha = new \DateTime($anno . '-' . $mes . '-1');
			
			$in = $hoy->diff($fecha);
			
			if($in->invert == 1){
				$em->persist($ordenMesActual);
				$em->flush();
			}			
		}

		return $res;
	}
}
?>