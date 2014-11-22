<?php 
namespace RGM\eLibreria\VentasBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ContenidoDistribuidora{
	private $distribuidora;
	private $existencias;
	private $totalParcial = 0.0;
	private $totalIVAParcial = 0.0;
	
	public function __construct($d){
		$this->distribuidora = $d;		
		$this->existencias = new ArrayCollection();
	}
	
	public function getDistribuidora(){
		return $this->distribuidora;
	}
	
	public function getExistencias(){
		return $this->existencias;
	}
	
	public function addExistencia($e){
		$precio = $e->getItemVenta()->getPrecioVenta();
		$iva = $e->getIVA();
		
		$this->totalParcial += $precio;
		$this->totalIVAParcial += $precio * $iva;
		
		$this->existencias->add($e);
	}
	
	public function getTotalParcialDistribuidora(){
		return $this->totalParcial;
	}
	
	public function getTotalIVAParcialDistribuidora(){
		return $this->totalIVAParcial;
	}
}
?>