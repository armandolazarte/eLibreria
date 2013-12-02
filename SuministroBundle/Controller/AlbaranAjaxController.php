<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

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
			$id = $peticion->request->get('idAlbaran');
			$numero = $peticion->request->get('numeroAlbaran');
			$fecha_real = $peticion->request->get('fechaRealizacion');
			$fecha_vencimiento = $peticion->request->get('fechaVencimiento');
			$contrato_id = $peticion->request->get('contratoId');
			
			if($numero != "" && $fecha_real != "" && $fecha_vencimiento != "" && $contrato_id != ""){
				$em = $this->getEm();
				
				$entidad = null;
				
				if($id == ""){
					$entidad = $this->getNuevaInstancia($this->getParametro('clase_entidad'));					
				}
				else{
					$entidad = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
				}
				
				$entidad->setNumeroAlbaran($numero);
				
				$info_contrato = $this->getParametro('contrato');
				$contrato = $em->getRepository($info_contrato['repositorio'])->find($contrato_id);
				$entidad->setContrato($contrato);

				$entidad->setFechaRealizacion(new \DateTime($fecha_real));
				$entidad->setFechaVencimiento(new \DateTime($fecha_vencimiento));
				
				$em->persist($entidad);
				$em->flush();

				$res['idAlbaran'] = $entidad->getId();
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
	
	public function getDatosLibroPlantillaAction(){
		//Sacar todos los autores y estilos

		$infoAutores = $this->getParametro('autor');
		$infoEstilos = $this->getParametro('estilo');
		
		$em = $this->getEm();
		$opciones = array();
		
		$opciones['autores'] = $em->getRepository($infoAutores['repositorio'])->findAll();
		$opciones['estilos'] = $em->getRepository($infoEstilos['repositorio'])->findAll();
		
		return $this->render($this->getPlantilla('plantillaDatosLibro'), $opciones);
	}
	
	public function getEjemplaresLibroPlantillaAction(){
		return $this->render($this->getPlantilla('plantillaEjemplaresLibro'));
	}
	
	public function getDatosLibroAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
			
			if($isbn != ""){
				$em = $this->getEm();
				
				$infoLibro = $this->getParametro('libro');
				$libro = $em->getRepository($infoLibro['repositorio'])->find($isbn);
				
				if($libro){
					$autores = array();
					$estilos = array();

					foreach($libro->getAutores() as $autor){
						$autores[] = $autor->getId();
					}
					
					foreach($libro->getEstilos() as $estilo){
						$estilos[] = $estilo->getId();
					}
					
					$res['autores'] = $autores;
					$res['estilos'] = $estilos;
				}				
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
			$autores_ids = json_decode($peticion->request->get('autores'));
			$estilos_ids = json_decode($peticion->request->get('estilos'));
			
			if($isbn != "" && $titulo != ""){
				$em = $this->getEm();
				
				$editorial = null;
				if($editorial_nombre != ""){
					$info_editorial = $this->getParametro('editorial');
					$editorial = $em->getRepository($info_editorial['repositorio'])->findOneBy(array(
							'nombre' => $editorial_nombre		
					));
					
					if(!$editorial){
						$editorial = $this->getNuevaInstancia($info_editorial['entidad']);
						$editorial->setNombre($editorial_nombre);
						
						$em->persist($editorial);
					}
				}
				
				$autores = new ArrayCollection();				
				if(!empty($autores_ids)){
					$infoAutor = $this->getParametro('autor');
					foreach($autores_ids as $idAutor){
						$autor = $em->getRepository($infoAutor['repositorio'])->find($idAutor);
						
						if($autor){
							$autores[] = $autor;
						}
					}
				}
				
				$estilos = new ArrayCollection();
				if(!empty($estilos_ids)){
					$infoEstilo = $this->getParametro('estilo');
					foreach($estilos_ids as $idEstilo){
						$estilo = $em->getRepository($infoEstilo['repositorio'])->find($idEstilo);
				
						if($estilo){
							$estilos[] = $estilo;
						}
					}
				}
					
				$info_libro = $this->getParametro('libro');
				$libro = $this->getEm()->getRepository($info_libro['repositorio'])->find($isbn);
				
				if(!$libro){
					$libro = $this->getNuevaInstancia($info_libro['entidad']);
					
					$libro->setIsbn($isbn);
				}
				
				$libro->setTitulo($titulo);
				if($editorial){
					$libro->setEditorial($editorial);
				}
				
				$libro->setAutores($autores);
				$libro->setEstilos($estilos);
					
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
		$infoLoc = $this->getParametro('localizacion');
		$em = $this->getEm();
		
		$opciones['localizaciones'] = $em->getRepository($infoLoc['repositorio'])->findAll();
		
		return $this->render($this->getPlantilla('ejemplar'), $opciones);
	}
	
	private function getResponse(array $respuesta){
		$response = new Response(json_encode($respuesta));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
}

?>