<?php 

namespace RGM\eLibreria\VentasBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

/* SELECT * FROM venta v 
 * 		JOIN itemventa iv 
 * 		JOIN existencia e 
 * 		JOIN itemalbaran ia 
 * 		JOIN albaran a 
 * 		JOIN contratosuministro cs 
 * 		JOIN distribuidora d 
 * 			ON v.id=iv.venta_id 
 * 			AND iv.existencia_id=e.id 
 * 			AND ia.existencia_id=e.id 
 * 			AND a.id=ia.albaran_id 
 * 			AND a.contrato_id=cs.id 
 * 			AND cs.distribuidora_id=d.id
 * 				WHERE v.fecha LIKE "2014-03-%"
 */

class RepositorioVentas extends EntityRepository
{
	public function findVentasPorFecha($mes, $anno)
	{
		$em = $this->getEntityManager();		
		$qb = $em->createQueryBuilder();
		
		$qb->select('v')
			->from('RGMELibreriaVentasBundle:Venta', 'v')
				->where('v.fecha LIKE ?1');
		
		$mesString = str_pad($mes, 2, '0', STR_PAD_LEFT);
		
		$qb->setParameter(1, $anno . '-' . $mesString . '%');		
		
		$q = $qb->getQuery();
		
		return $q->getResult();
	}
	
	public function findVentasPorFechaOrdenado($mes, $anno, $orden = 'ASC')
	{
		$em = $this->getEntityManager();		
		$qb = $em->createQueryBuilder();
		
		$qb->select('v')
			->from('RGMELibreriaVentasBundle:Venta', 'v')
				->where('v.fecha LIKE ?1')
				->orderBy('v.fecha', $orden);
		
		$mesString = str_pad($mes, 2, '0', STR_PAD_LEFT);
		
		$qb->setParameter(1, $anno . '-' . $mesString . '%');		
		
		$q = $qb->getQuery();
		
		return $q->getResult();
	}
}
?>