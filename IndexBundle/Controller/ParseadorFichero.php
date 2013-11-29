<?php 
namespace RGM\eLibreria\IndexBundle\Controller;

class ParseadorFichero{
	private $fichero;
	
	public function __construct($fichero){
		$this->fichero = $fichero;
	}
	
	public function parsearFichero(){
		$fichero = $this->fichero;
		
		$res = array();
		$lectura = array();
		$keys = array();
	
		if(($pF = fopen($fichero, 'r')) != false){
			while(!feof($pF)){
				$lectura[] = fgets($pF);
			}
				
			fclose($pF);
		}
	
		$keys = explode(";", $lectura[0]);
		$claves = array();
		$lineas = array();
	
		foreach($keys as $k){
			$k = str_replace("\"", "", $k);
			$k = str_replace("\n", "", $k);
			$k = str_replace("\r", "", $k);
				
			$claves[] = strtolower($k);
		}
	
		for($i = 1; $i < count($lectura) - 1; $i++){
			$linea = explode(";", $lectura[$i]);
				
			$lineaAux = array();
			$j = 0;
				
			foreach($claves as $c){
				$linea[$j] = str_replace("\"", "", $linea[$j]);
				$linea[$j] = str_replace("\n", "", $linea[$j]);
				$linea[$j] = str_replace("\r", "", $linea[$j]);
	
				$lineaAux[$c] = ucfirst(strtolower($linea[$j]));
	
				$j++;
			}
				
			$lineas[] = $lineaAux;
		}
	
		return $lineas;
	}
}
?>