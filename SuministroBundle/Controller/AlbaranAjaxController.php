<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbaranAjaxController extends Asistente{
	private $bundle = '';
	private $controlador = '';

	public function __construct() {
		parent::__construct(
				$this->bundle, 
				$this->controlador);
	}
	
	public function registroAlbaranAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$numero = $peticion->request->get('numero');
			$fecha_real = $peticion->request->get('fecha_real');
			$fecha_vencimiento = $peticion->request->get('fecha_vencimiento');
			$contrato_id = $peticion->request->get('contrato_id');
			
			if($numero != "" && $fecha_real != "" && $fecha_vencimiento != "" && $contrato_id != ""){
				$res['estado'] = true;
			}
		}
		
		$response = new Response(json_encode($res));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function getLibrosAlbaranAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$id_albaran = $peticion->request->get('id_albaran');
			
			if($id_albaran != ""){
				$res['estado'] = true;
				$res['libros'] = array(
						'9788466222938',
						'9788421663165',
						'9788466216081',
						'9788466223768',
						'9788425509087',
				);
			}
		}
		
		$response = new Response(json_encode($res));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function getDatosLibroAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
			
			if($isbn != ""){
				$res['estado'] = true;
				$res['isbn'] = $isbn;
				$res['titulo'] = 'Libro' . (rand() % 100);
			}
		}
		
		$response = new Response(json_encode($res));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
}

?>