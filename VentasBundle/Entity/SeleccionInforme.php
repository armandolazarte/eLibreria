<?php
namespace RGM\eLibreria\VentasBundle\Entity;

class SeleccionInforme {
	private $dia;
	private $mes;
	private $anno;
	private $mesString = array(1 => 'Enero', 
			2 => 'Febrero',  
			3 => 'Marzo',  
			4 => 'Abril',  
			5 => 'Mayo',  
			6 => 'Junio',  
			7 => 'Julio',  
			8 => 'Agosto',  
			9 => 'Septiembre',  
			10 => 'Octubre',  
			11 => 'Noviembre',  
			12 => 'Diciembre');

	public function getDia(){
		return $this->dia;
	}
	
	public function setDia($dia){
		$this->dia = $dia;
	}
	
	public function getMes() {
		return $this->mes;
	}

	public function getMesString() {
		return $this->mesString[$this->mes];
	}

	public function setMes($mes) {
		$this->mes = $mes + 1;
	}

	public function getAnno() {
		return $this->anno;
	}

	public function setAnno($anno) {
		$this->anno = $anno;
	}
}
?>