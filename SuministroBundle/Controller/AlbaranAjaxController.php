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
	
	public function getItemsAlbaranAction(Request $peticion){
		$res = array();
		$res['estado'] = false;
		
		if($peticion->getMethod() == "POST"){
			$idAlbaran = $peticion->request->get('idAlbaran');
			
			if($idAlbaran != ""){
				$em = $this->getEm();
				
				$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($idAlbaran);
				
				if($albaran){					
					$res['items'] = array();
					foreach($albaran->getItems() as $item){
						$elemento = $item->getExistencia();
						
						
						
						$clase = get_class($elemento);
						$array = explode('\\', $clase);
						$clase = strtolower(array_pop($array));

						if($clase == 'existencialibro'){
							$isbn = $elemento->getLibro()->getIsbn();
							
							if(! array_key_exists($isbn, $res['items'])){
								$arrayLibro = array();
								
								$arrayLibro['tipo'] = 'libro';
								$arrayLibro['existencias'] = array();
								
								$res['items'][$isbn] = $arrayLibro;
							}
							
							var_dump($elemento->getId());
							
							$res['items'][$isbn]['existencias'][] = $elemento->getId(); 
						}
						else if($clase == 'existenciaarticulo'){
							$ref = $elemento->getArticulo()->getRef();
							if(! array_key_exists($ref, $res['items'])){
								$arrayArticulo = array();
								
								$arrayArticulo['tipo'] = 'articulo';
								$arrayArticulo['existencias'] = array();
								
								$res['items'][$ref] = $arrayArticulo;
							}	
							
							var_dump($elemento->getId());				
									
							$res['items'][$ref]['existencias'][] = $elemento->getId();
						}
					}
					
					$res['estado'] = true;
				}
			}
		}
		
		return $this->getResponse($res);
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
					$res['titulo'] = $libro->getTitulo();	

					$editorial = $libro->getEditorial();
					
					if($editorial){
						$res['editorial'] = $editorial->getNombre();
					}
										
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
	
	public function getDatosEjemplarAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$idEjemplar = $peticion->request->get('idEjemplar');
			
			if($idEjemplar != ""){
				$em = $this->getEm();
				
				$infoEjemplar = $this->getParametro('ejemplar');
				$ejemplar = $em->getRepository($infoEjemplar['repositorio'])->find($idEjemplar);
				
				if($ejemplar){
					$res['localizacion'] = $ejemplar->getLocalizacion()->getId();					
					$res['precio'] = $ejemplar->getPrecio();		
					$res['iva'] = $ejemplar->getIva();		
					$res['descuento'] = $ejemplar->getItemAlbaran()->getDescuento();		
					$res['adquirido'] = $ejemplar->getAdquirido();		
					$res['vendido'] = $ejemplar->getVendido();
					
					$res['estado'] = true;
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
				
				$res['isbn'] = $isbn;
				$res['estado'] = true;
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function eliminarLibroAction(){
		return $this->getResponse(array('estado' => true));
	}
	
	public function getPlantillaLibroAction(){
		//Sacar todos los autores y estilos

		$infoAutores = $this->getParametro('autor');
		$infoEstilos = $this->getParametro('estilo');
		
		$em = $this->getEm();
		$opciones = array();
		
		$opciones['autores'] = $em->getRepository($infoAutores['repositorio'])->findAll();
		$opciones['estilos'] = $em->getRepository($infoEstilos['repositorio'])->findAll();
		
		return $this->render($this->getPlantilla('plantillaLibro'), $opciones);
	}
	
	public function getPlantillaEjemplarAction(){
		$infoLoc = $this->getParametro('localizacion');
		$em = $this->getEm();
		
		$opciones['localizaciones'] = $em->getRepository($infoLoc['repositorio'])->findAll();
		
		return $this->render($this->getPlantilla('plantillaEjemplar'), $opciones);
	}
	
	private function getResponse(array $respuesta){
		$response = new Response(json_encode($respuesta));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function getExistenciasLibroAlbaranAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
			$albaran = $peticion->request->get('idAlbaran');
			
			$em = $this->getEm();
			
			$sql = 'SELECT * FROM ItemAlbaran i, Existencia e, existenciaLibro eL WHERE i.albaran_id = :albaran AND eL.libro_id = :isbn AND i.existencia_id = e.id AND e.id = eL.id';

			$stmt = $em->getConnection()->prepare($sql);
			$stmt->bindValue(":albaran", $albaran);
			$stmt->bindValue(":isbn", $isbn);
			$stmt->execute();
			
			$resultados = $stmt->fetchAll();
			
			foreach($resultados as $r){
				$res['existenciasLibro'][] = $r['existencia_id'];
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function borrarEjemplarAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$idEjemplar = $peticion->request->get('id');
			$infoEjemplar = $this->getParametro('ejemplar');
			$em = $this->getEm();
			
			$ejemplar = $em->getRepository($infoEjemplar['repositorio'])->find($idEjemplar);
			
			if($ejemplar){
				$em->remove($ejemplar);
				$em->flush();

				$res['estado'] = true;
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function registroEjemplarAction(Request $peticion){
		$res['estado'] = false;
		$res['idEjemplar'] = "";
		
		if($peticion->getMethod() == "POST"){
			$isbn = $peticion->request->get('isbn');
			$idAlbaran = $peticion->request->get('albaran');

			$idEjemplar = $peticion->request->get('id');
			$idLoc = $peticion->request->get('localizacion');
			$precio = $peticion->request->get('precio');
			$iva = $peticion->request->get('iva');
			$descuento = $peticion->request->get('descuento');
			$vendido = $peticion->request->get('vendido');
			$adquirido = $peticion->request->get('adquirido');
			
			if($isbn != "" && $idLoc != "" &&
					$precio != "" && $iva != "" && $descuento != "" &&
					$vendido != "" && $adquirido != ""){
				$em = $this->getEm();
				
				$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($idAlbaran);
				
				if($albaran){				
					$infoLibro = $this->getParametro('libro');
					
					$libro = $em->getRepository($infoLibro['repositorio'])->find($isbn);
					
					if($libro){
						$infoEjemplar = $this->getParametro('ejemplar');
						$ejemplar = null;
						$item = null;
						
						$infoItem = $this->getParametro('itemAlbaran');
						
						if($idEjemplar != ""){
							//Actualizar ejemplar
							$ejemplar = $em->getRepository($infoEjemplar['repositorio'])->find($idEjemplar);
							$item = $ejemplar->getItemAlbaran();
						}
						else{
							//Crear nuevo ejemplar
							$ejemplar = $this->getNuevaInstancia($infoEjemplar['entidad'], array($libro));
							$item = $this->getNuevaInstancia($infoItem['entidad']);
						
							$item->setEjemplar($ejemplar);
							$item->setAlbaran($albaran);
						}
						
						$infoLocalizacion = $this->getParametro('localizacion');
						$loc = $em->getRepository($infoLocalizacion['repositorio'])->find($idLoc);
						
						$ejemplar->setLocalizacion($loc);
						$ejemplar->setPrecio($precio);
						$ejemplar->setIva($iva);
						if($vendido == "true"){
							$ejemplar->setVendido(1);	
						}
						else{
							$ejemplar->setVendido(0);
						}
						
						if($adquirido == "true"){
							$ejemplar->setAdquirido(1);	
						}
						else{
							$ejemplar->setAdquirido(0);
						}
						
						$em->persist($ejemplar);
						$item->setDescuento($descuento);
						
						$em->persist($item);
						
						$em->flush();
						
						$res['estado'] = true;
						$res['idEjemplar'] = $ejemplar->getId();
					}
				}
			}
		}
				
		return $this->getResponse($res);
	}
}
?>