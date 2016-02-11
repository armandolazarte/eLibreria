<?php 
namespace RGM\eLibreria\FacturacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;

class RecibidasController extends Asistente{
	private $bundle = 'facturacionbundle';
	private $controlador = 'recibidas';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function verFacturasAction(Request $peticion){
		
	}
}

?>