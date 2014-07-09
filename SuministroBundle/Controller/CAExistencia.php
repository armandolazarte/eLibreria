<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use RGM\eLibreria\ExistenciaBundle\Entity\Existencia;

class CAExistencia{	
	private $existencias;
	
	public function __construct(){
		$this->existencias = new ArrayCollection();
	}
	
	public function addExistencia(Existencia $e){
		$this->existencias->add($e);
	}
	
	public function getNumeroDeExistencias(){
		return count($this->existencias);
	}
	
	public function getNumeroDeExistenciasVendidas(){
		$vendidas = 0;
		
		foreach($this->existencias as $existencia){ 
			if($existencia->isVendido()){
				$vendidas++;
			}
		}
		
		return $vendidas;
	}
	
	public function getBaseImponible(){
		$base = 0;
		
		foreach($this->existencias as $existencia){
			$descuento = $existencia->getItemAlbaran()->getDescuento();
			$precio = $existencia->getPrecio();
			
			$base += $precio * (1 - $descuento);
		}
		
		return $base;
	}
	
	public function getBaseImponibleVendido(){
		$base = 0;
		
		foreach($this->existencias as $existencia){
			if($existencia->isVendido()){
				$descuento = $existencia->getItemAlbaran()->getDescuento();
				$base += $existencia->getPrecio() * (1 - $descuento);
			}
		}
		
		return $base;
	}
	
	public function getLocalizaciones(){
		$localizaciones = array();
		
		foreach($this->existencias as $existencia){
			if(! $existencia->isVendido()){
				$loc = $existencia->getLocalizacion();
				if($loc){
					$localizaciones[] = $loc;
				}
				else{
					$localizaciones[] = 'Sin localización';
				}
			}
		}
		
		return $localizaciones;
	}
	
	public function getIva(){
		return ($this->existencias->first()->getIva());
	}
	
	public function getDescuento(){
		return ($this->existencias->first()->getItemAlbaran()->getDescuento());
	}
	
	public function getPrecioUnitario(){
		return ($this->existencias->first()->getPrecio());
	}
	
	public function getBaseIVA(){
		return $this->getBaseImponible() * $this->getIva();
	}
	
	public function getPrecioIVA(){
		$e = $this->existencias->first();
		
		return ($e->getPrecio() * (1 + $e->getIva()));
	}
}
?>