<?php 
namespace RGM\eLibreria\VentasBundle\Entity;

use RGM\eLibreria\VentasBundle\Entity\ItemVenta;

class DispensadorItemsVenta{
	private $items;
	private $venta;
	
	public function __construct($venta, $items){
		$this->items = $items;
		$this->venta = $venta;
	}
	
	public function getItem(){
		$itemVuelta = array_shift($this->items);
		
		if(! $itemVuelta){
			$itemVuelta = new ItemVenta($this->venta);
		}
		
		return $itemVuelta;
	}
	
	public function terminarTransaccion($em){
		foreach($this->items as $item){
			$em->remove($item);
		}
	}
}
?>