<?php 
namespace RGM\eLibreria\FacturacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;

class FlujoCajaController extends Asistente{
	private $bundle = 'facturacionbundle';
	private $controlador = 'flujocaja';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function verFlujoCajaAction(Request $peticion){
		
	}
}

?>