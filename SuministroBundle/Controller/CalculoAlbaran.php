<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\SuministroBundle\Entity\Albaran;
use Doctrine\Common\Collections\ArrayCollection;

class CalculoAlbaran{
	private $albaran;
	private $existencias;
	private $recargos;
	
	public function __construct(Albaran $albaran, $recargos){
		$this->albaran = $albaran;
		$this->existencias = new ArrayCollection();
		$this->recargos = $recargos;
		
		$this->construirMapa();
	}
	
	private function construirMapa(){		
		foreach($this->albaran->getItems() as $item){
			$existencia = $item->getExistencia();				 
			$referencia = $existencia->getReferencia();
				 
			if($this->existencias->containsKey($referencia)){
				$listadoExistencias = $this->existencias->get($referencia);
			}
			else{
				$listadoExistencias = new CAExistencia();
			}
			
			$listadoExistencias->addExistencia($existencia);
			
			$this->existencias->set($referencia, $listadoExistencias);
		}
	}
	
	public function getOpcionesVista(){
		$res = array();
		
		$bImp = 0;
		$bIVA = 0;
		$bRec = 0;
		$bases = new ArrayCollection();
		$vTotal = 0;
		$bTotal = 0;
		$lineas = array();
		
		foreach($this->existencias as $CAExistencia){			
			$ivaActual = (string)$CAExistencia->getIVA();
			
			$bImpActual = $CAExistencia->getBaseImponible();
			$bIVAActual = $CAExistencia->getBaseIVA();
			$bRecActual = $CAExistencia->getBaseRecargo($this->recargos);
			
			$bImp += $bImpActual;
			$bIVA += $bIVAActual;
			$bRec += $bRecActual;
			
			if($bases->containsKey($ivaActual)){
				$base = $bases->get($ivaActual);
				
				$base['bImp'] += $bImpActual;
				$base['bIVA'] += $bIVAActual;
				$base['bRec'] += $bRecActual;
				
				$bases->set($ivaActual, $base);
			}
			else{
				$base = array();

				$base['iva'] = $CAExistencia->getIVA();
				$base['rec'] = $this->recargos->findRecargoIVA($CAExistencia->getIVA())->getRecargo();
				$base['bImp'] = $bImpActual;
				$base['bIVA'] = $bIVAActual;
				$base['bRec'] = $bRecActual;
				
				$bases->set($ivaActual, $base);
			}
			
			$bVendido = $CAExistencia->getBaseImponibleVendido();

			if($bVendido > 0){
				$vTotal += $bVendido + $bIVAActual + $bRecActual;
			}
			
			$bTotal += $bImpActual + $bIVAActual + $bRecActual;
			
			$l = array();
			$l['tipo'] = 0; //0 = Existencia; 1 = Localizacion
			$l['ref'] = $CAExistencia->getReferencia();
			$l['titulo'] = $CAExistencia->getTitulo();
			$l['numEjemplares'] = $CAExistencia->getNumeroDeExistencias();
			$l['vendidos'] = $CAExistencia->getNumeroDeExistenciasVendidas();
			$l['pvpConIVA'] = $CAExistencia->getPrecioIVA();
			$l['pvpSinIVA'] = $CAExistencia->getPrecioUnitario();
			$l['desc'] = $CAExistencia->getDescuento();
			$l['importe'] = $CAExistencia->getBaseImponible();
			$l['iva'] = $CAExistencia->getIva();
			
			$lineas[] = $l;
			
			foreach($CAExistencia->getLocalizaciones() as $loc){
				$l = array();
				$l['tipo'] = 1;
				$l['localizacion'] = $loc;
				$lineas[] = $l;
			}			
		}

		$res['albaran'] = $this->albaran;
		$res['bImp'] = $bImp;
		$res['bIVA'] = $bIVA;
		$res['bRec'] = $bRec;
		$res['bases'] = $bases;
		$res['vTotal'] = $vTotal;
		$res['bTotal'] = $bTotal;
		$res['lineas'] = $lineas;
		
		return $res;
	}
}
?>