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
	
	private function getResponse(array $respuesta){
		$response = new Response(json_encode($respuesta));
		$response->headers->set('Content-Type', 'application/json');
	
		return $response;
	}	

// 	registroAlbaran
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

// 	peticionItems
	public function peticionItemsAction(Request $peticion){
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
								
							$res['items'][$ref]['existencias'][] = $elemento->getId();
						}
					}
						
					$res['estado'] = true;
				}
			}
		}
	
		return $this->getResponse($res);
	}

// 	peticionDatosLibro
	public function peticionDatosLibroAction(Request $peticion){
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

// 	peticionDatosArticulo


// 	peticionDatosExistencia
	public function peticionDatosExistenciaAction(Request $peticion){
		$res = array();
	
		if($peticion->getMethod() == "POST"){
			$idExistencia = $peticion->request->get('idExistencia');
			$tipoObjeto = $peticion->request->get('tipoPadre');
				
			if($idExistencia != ""){
				$em = $this->getEm();
	
				$infoPadres = $this->getParametro('padres');
				$existencia = $em->getRepository($infoPadres[$tipoObjeto]['repositorio'])->find($idExistencia);
	
				if($existencia){
					$res['localizacion'] = $existencia->getLocalizacion()->getId();
					$res['precio'] = $existencia->getPrecio();
					$res['iva'] = $existencia->getIva();
					$res['descuento'] = $existencia->getItemAlbaran()->getDescuento();
					$res['adquirido'] = $existencia->getAdquirido();
					$res['vendido'] = $existencia->getVendido();
						
					$res['estado'] = true;
				}
			}
		}
	
		return $this->getResponse($res);
	}

// 	registroLibro
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

// 	registroArticulo


// 	registroExistencia
	public function registroExistenciaAction(Request $peticion){
		$res['estado'] = false;
		$res['idExistencia'] = "";
	
		if($peticion->getMethod() == "POST"){
			$idAlbaran = $peticion->request->get('albaran');
				
			$idPadre = $peticion->request->get('idPadre');
			$tipoPadre = $peticion->request->get('tipo');
				
			$idExistencia = $peticion->request->get('id');
				
			$idLoc = $peticion->request->get('localizacion');
			$precio = $peticion->request->get('precio');
			$iva = $peticion->request->get('iva');
			$descuento = $peticion->request->get('descuento');
			$vendido = $peticion->request->get('vendido');
			$adquirido = $peticion->request->get('adquirido');
				
			if($idPadre != "" && $idLoc != "" &&
			$precio != "" && $iva != "" && $descuento != "" &&
			$vendido != "" && $adquirido != ""){
				$em = $this->getEm();
	
				$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($idAlbaran);
	
				if($albaran){
					$infoPadre = $this->getParametro($tipoPadre);
					$padre = $em->getRepository($infoPadre['repositorio'])->find($idPadre);
						
					if($padre){
						$infoExistencias = $this->getParametro('padres');
						$ejemplar = null;
						$item = null;
	
						$infoItem = $this->getParametro('itemAlbaran');
	
						if($idExistencia != ""){
							//Actualizar ejemplar
							$existencia = $em->getRepository($infoExistencias[$tipoPadre]['repositorio'])->find($idExistencia);
							$item = $existencia->getItemAlbaran();
						}
						else{
							//Crear nuevo ejemplar
							$existencia = $this->getNuevaInstancia($infoExistencias[$tipoPadre]['clase'], array($padre));
							$item = $this->getNuevaInstancia($infoItem['entidad']);
	
							$item->setExistencia($existencia);
							$item->setAlbaran($albaran);
						}
	
						$infoLocalizacion = $this->getParametro('localizacion');
						$loc = $em->getRepository($infoLocalizacion['repositorio'])->find($idLoc);
	
						$existencia->setLocalizacion($loc);
						$existencia->setPrecio($precio);
						$existencia->setIva($iva);
						if($vendido == "true"){
							$existencia->setVendido(1);
						}
						else{
							$existencia->setVendido(0);
						}
	
						if($adquirido == "true"){
							$existencia->setAdquirido(1);
						}
						else{
							$existencia->setAdquirido(0);
						}
	
						$em->persist($existencia);
						$item->setDescuento($descuento);
	
						$em->persist($item);
	
						$em->flush();
	
						$res['estado'] = true;
						$res['idExistencia'] = $existencia->getId();
					}
				}
			}
		}
	
		return $this->getResponse($res);
	}

// 	borrarExistencia
	public function borrarExistenciaAction(Request $peticion){
		$res = array();
	
		if($peticion->getMethod() == "POST"){
			$idExistencia = $peticion->request->get('id');
			$tipoExistencia = $peticion->request->get('tipo');
			$infoPadres = $this->getParametro('padres');
			$em = $this->getEm();
				
			$existencia = $em->getRepository($infoPadres[$tipoExistencia]['repositorio'])->find($idExistencia);
				
			if($existencia){
				$em->remove($existencia);
				$em->flush();
	
				$res['estado'] = true;
			}
		}
	
		return $this->getResponse($res);
	}

// 	plantillaLibro
	public function plantillaLibroAction(){
		//Sacar todos los autores y estilos
	
		$infoAutores = $this->getParametro('autor');
		$infoEstilos = $this->getParametro('estilo');
	
		$em = $this->getEm();
		$opciones = array();
	
		$opciones['autores'] = $em->getRepository($infoAutores['repositorio'])->findAll();
		$opciones['estilos'] = $em->getRepository($infoEstilos['repositorio'])->findAll();
	
		return $this->render($this->getPlantilla('plantillaLibro'), $opciones);
	}

// 	plantillaArticulo


// 	plantillaExistencia
	public function plantillaExistenciaAction(){
		$infoLoc = $this->getParametro('localizacion');
		$em = $this->getEm();
	
		$opciones['localizaciones'] = $em->getRepository($infoLoc['repositorio'])->findAll();
	
		return $this->render($this->getPlantilla('plantillaExistencia'), $opciones);
	}
}
?>