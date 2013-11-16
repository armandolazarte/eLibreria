<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AlbaranAjaxController extends Asistente{
	private $bundle = 'suministrobundle';
	private $controlador = 'albaran';

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
// 				$em = $this->getEm();
				
// 				$entidad = $em->getRepository($this->getNombreEntidad($this->getParametro('entidad')))->findOneBy(array(
// 						'numeroAlbaran' => $numero
// 				));
				
// 				if(!$entidad){
// 					$entidad = $this->getNuevaInstancia($this->getParametro('clase_entidad'));
// 					$entidad->setNumeroAlbaran($numero);
					
// 					$info_contrato = $this->getParametro('contrato');
// 					$contrato = $em->getRepository($info_contrato['repositorio'])->find($contrato_id);
// 					$entidad->setContrato($contrato);

// 					$entidad->setFechaRealizacion(new \DateTime($fecha_real));
// 					$entidad->setFechaVencimiento(new \DateTime($fecha_vencimiento));
					
// 					$em->persist($entidad);
// 					$em->flush();
// 				}
				
				$res['estado'] = true;
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function getLibrosAlbaranAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$id_albaran = $peticion->request->get('id_albaran');
			
			if($id_albaran != ""){
				$res['estado'] = true;
				$res['libros'] = array(
// 						'9788466222938',
// 						'9788421663165',
// 						'9788466216081',
// 						'9788466223768',
// 						'9788425509087',
				);
			}
		}
		
		return $this->getResponse($res);
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
		
		return $this->getResponse($res);
	}
	
	public function registroLibroAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
			$titulo = $peticion->request->get('titulo');
			$editorial_nombre = $peticion->request->get('editorial');
			
			if($isbn != "" && $titulo != "" && $editorial_nombre != ""){
				$em = $this->getEm();
				
				$info_editorial = $this->getParametro('editorial');
				$editorial = $em->getRepository($info_editorial['repositorio'])->findOneBy(array(
						'nombre' => $editorial_nombre		
				));
				
				if(!$editorial){
					$editorial = $this->getNuevaInstancia($info_editorial['entidad']);
					$editorial->setNombre($editorial_nombre);
					
					$em->persist($editorial);
				}
				
				$info_libro = $this->getParametro('libro');
				$libro = $this->getEm()->getRepository($info_libro['repositorio'])->find($isbn);
				
				if(!$libro){
					$libro = $this->getNuevaInstancia($info_libro['entidad']);
					
					$libro->setIsbn($isbn);
					$libro->setTitulo($titulo);
					$libro->setEditorial($editorial);
				}
				else{
					if($libro->getTitulo() != $titulo){
						$libro->setTitulo($titulo);
					}
					
					$editorial_ant = $libro->getEditorial();
					
					if($editorial_ant){
						if($editorial_ant->getNombre() != $editorial->getNombre()){
							$libro->setEditorial($editorial);
						}
					}
					else{
						$libro->setEditorial($editorial);
					}
				}
					
				$em->persist($libro);
				$em->flush();
				
				$res['estado'] = true;
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function eliminarLibroAction(){
		return $this->getResponse(array('estado' => true));
	}
	
	public function getPlantillaEjemplarAction(){
		return $this->render($this->getPlantilla('ejemplar'));
	}
	
	private function getResponse(array $respuesta){
		$response = new Response(json_encode($respuesta));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
}

?>