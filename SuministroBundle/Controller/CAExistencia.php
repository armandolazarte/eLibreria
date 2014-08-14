<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use RGM\eLibreria\ExistenciaBundle\Entity\Existencia;

class CAExistencia{	
	private $referencia;
	private $titulo;
	private $iva;
	private $baseImpTotal = 0;
	private $baseImpVendido = 0;
	private $numeroExistencias = 0;
	private $numeroExistenciasVendidas = 0;
	private $localizaciones;
	private $existencias;
	
	public function __construct(){
		$this->existencias = new ArrayCollection();
		$this->localizaciones = new ArrayCollection();
	}
	
	public function addExistencia(Existencia $e){
		$this->existencias->add($e);
		
		if($this->titulo == null){
			$this->titulo = $e->getObjetoVinculado()->getTitulo();
		}
		
		if($this->referencia == null){
			$this->referencia = $e->getReferencia();
		}
		
		if($this->iva == null){
			$this->iva = $e->getIva();
		}
		
		$this->numeroExistencias++;
		
		$descuento = $e->getItemAlbaran()->getDescuento();
		$precio = $e->getPrecio();
			
		$this->baseImpTotal += $precio * (1 - $descuento);
		
		if($e->isVendido() || $e->isAdquirido()){
			$this->numeroExistenciasVendidas++;
			$this->baseImpVendido += $precio * (1 - $descuento);
		}
		else{
			$loc = $e->getLocalizacion();
			if($loc){
				$this->localizaciones->add($loc->getDenominacion());
			}
			else{
				$this->localizaciones->add('Sin Localización');
			}
		}
	}
	
	public function getTitulo(){
		return $this->titulo;
	}
	
	public function getReferencia(){
		return $this->referencia;
	}
	
	public function getNumeroDeExistencias(){
		return $this->numeroExistencias;
	}
	
	public function getNumeroDeExistenciasVendidas(){
		return $this->numeroExistenciasVendidas;
	}
	
	public function getIva(){
		return $this->iva;
	}
	
	public function getBaseImponible(){
		return $this->baseImpTotal;
	}
	
	public function getBaseImponibleVendido(){
		return $this->baseImpVendido;
	}
	
	public function getLocalizaciones(){		
		return $this->localizaciones;
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