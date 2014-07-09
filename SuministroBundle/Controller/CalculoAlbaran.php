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
		$bases = array();
		$vTotal = 0;
		$bTotal = 0;
		$lineas = array();
		$numPaginas = 0;
		
		foreach($this->existencias as $CAExistencia){
			$bImp += $CAExistencia->getBaseImponible();
			$bIVA += $CAExistencia->getBaseIVA();
			$bRec += $CAExistencia->getBaseRecargo($this->recargos);
		}

		$res['bImp'] = $bImp;
		$res['bIVA'] = $bIVA;
		$res['bRec'] = $bRec;
		$res['bases'] = $bases;
		$res['vTotal'] = $vTotal;
		$res['bTotal'] = $bTotal;
		$res['lineas'] = $lineas;
		$res['numPaginas'] = $numPaginas;
		
		return $res;
	}
}
?>