<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GeneradorVentaController extends Asistente{
	private $bundle = 'ventasbundle';
	private $controlador = 'generador';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	private function getResponse(array $respuesta){
		$response = new Response(json_encode($respuesta));
		$response->headers->set('Content-Type', 'application/json');
		
		return $response;
	}
	
	public function getInfoClienteAction(Request $petecion){
		$res['estado'] = false;
		
		if($petecion->getMethod() == "POST"){
			$idCliente = $petecion->request->get('id');
			
			$em = $this->getEm();
			$infoClientes = $this->getParametro('cliente');
			$cliente = $em->getRepository($this->getEntidadLogico($infoClientes['repositorio']))->find($idCliente);
			
			if($cliente){
				$res['estado'] = true;

				$res['nombre'] = $cliente->getNombreContacto();
				$res['tel'] = $cliente->getTelefono();
				$res['movil'] = $cliente->getMovil();
				$res['dir'] = $cliente->getDireccion();
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function setNuevoClienteAction(Request $petecion){
		$res['estado'] = false;
		
		if($petecion->getMethod() == "POST"){
			$id = $petecion->request->get('id');
			
			$nombre = $petecion->request->get('nombre');
			$tel = $petecion->request->get('tel');
			$movil = $petecion->request->get('movil');
			$dir = $petecion->request->get('dir');
			
			$em = $this->getEm();
			
			$infoClientes = $this->getParametro('cliente');			
			$cliente = $this->getNuevaInstancia($infoClientes['clase']);
			$res['nuevo'] = true;

			if($id != ""){
				$clienteAux = $em->getRepository($this->getEntidadLogico($infoClientes['repositorio']))->find($id);
				
				if($clienteAux){
					$cliente = $clienteAux;
					$res['nuevo'] = false;
				}
			}
			
			$cliente->setNombreContacto($nombre);
			$cliente->setTelefono($tel);
			$cliente->setMovil($movil);
			$cliente->setDireccion($dir);
			
			$em->persist($cliente);
			$em->flush();
			
			$res['estado'] = true;
			$res['id'] = $cliente->getId();
		}
		
		return $this->getResponse($res);
	}
	
	private function buscarLocalizacion($idLoc){
		$em = $this->getEm();
		
		$infoLoc = $this->getParametro('localizacion');
		$loc = $em->getRepository($infoLoc['repositorio'])->find($idLoc);
		
		$res;
		
		if($loc){
			$res = $loc->getDenominacion();
		}
		
		return $res;
	}
	
	private function buscarTitulo($idEjemplar){
		$em = $this->getEm();
		
		$infoEjem = $this->getParametro('ejemplar');
		$ej = $em->getRepository($infoEjem['repositorio'])->find($idEjemplar);
		
		$res;
		
		if($ej){
			$res = $ej->getLibro()->getTitulo();
		}
		
		return $res;
	}
	
	public function buscarRefAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$ref = $peticion->request->get('ref');
			
			$buscar = $ref . '%';
			
			$em = $this->getEm();
			
			$sql = 'SELECT e.id, l.titulo, l.isbn, loc.denominacion FROM Existencia as e Natural Join existenciaLibro as eL, Libro as l, Localizacion as loc WHERE e.localizacion_id = loc.id  AND eL.libro_id = l.isbn AND e.vendido = 0 AND l.isbn LIKE :ref LIMIT 20';
			
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->bindValue(":ref", $buscar);
			$stmt->execute();
			
			$resultadosEjemplar = $stmt->fetchAll();
			
			foreach($resultadosEjemplar as $r){
				$obj = array();
				
				$obj['tipo'] = 'Libro';
				$obj['id'] = $r['id'];
				$obj['ref'] = $r['isbn'];
				$obj['titulo'] = $r['titulo'];
				$obj['loc'] = $r['denominacion'];
				
				$res[] = $obj;
			}

			$sql = 'SELECT e.id, a.titulo, a.ref, loc.denominacion FROM Existencia as e Natural Join existenciaArticulo as eA, Articulo as a, Localizacion as loc WHERE e.localizacion_id = loc.id AND e.vendido = 0 AND eA.articulo_id = a.id AND a.ref LIKE :ref LIMIT 20';
				
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->bindValue(":ref", $buscar);
			$stmt->execute();
				
			$resultadosArticulo = $stmt->fetchAll();
			
			foreach($resultadosArticulo as $r){
				$obj = array();
				
				$obj['tipo'] = 'Articulo';
				$obj['id'] = $r['id'];
				$obj['ref'] = $r['ref'];
				$obj['titulo'] = $r['titulo'];
				$obj['loc'] = $r['denominacion'];
				
				$res[] = $obj;
			}
		}
		
		return $this->getResponse(array('sugerencias' => $res));
	}
	
	public function buscarTituloAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$titulo = $peticion->request->get('titulo');
			
			$buscar = '%' . $titulo . '%';
				
			$em = $this->getEm();
				
			$sql = 'SELECT e.id, l.titulo, l.isbn, loc.denominacion FROM Existencia as e Natural Join existenciaLibro as eL, Libro as l, Localizacion as loc WHERE e.localizacion_id = loc.id  AND eL.libro_id = l.isbn AND e.vendido = 0 AND l.titulo LIKE :titulo LIMIT 20';
				
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->bindValue(":titulo", $buscar);
			$stmt->execute();
				
			$resultadosEjemplar = $stmt->fetchAll();
				
			foreach($resultadosEjemplar as $r){
				$obj = array();
				
				$obj['tipo'] = 'Libro';
				$obj['id'] = $r['id'];
				$obj['ref'] = $r['isbn'];
				$obj['titulo'] = $r['titulo'];
				$obj['loc'] = $r['denominacion'];
			
				$res[] = $obj;
			}
			
			$sql = 'SELECT e.id, a.titulo, a.ref, loc.denominacion FROM Existencia as e Natural Join existenciaArticulo as eA, Articulo as a, Localizacion as loc WHERE e.localizacion_id = loc.id AND e.vendido = 0 AND eA.articulo_id = a.id AND a.titulo LIKE :titulo LIMIT 20';
			
			$stmt = $em->getConnection()->prepare($sql);
			$stmt->bindValue(":titulo", $buscar);
			$stmt->execute();
			
			$resultadosArticulo = $stmt->fetchAll();
				
			foreach($resultadosArticulo as $r){
				$obj = array();
				
				$obj['tipo'] = 'Articulo';
				$obj['id'] = $r['id'];
				$obj['ref'] = $r['ref'];
				$obj['titulo'] = $r['titulo'];
				$obj['loc'] = $r['denominacion'];
			
				$res[] = $obj;
			}
		}
		
		return $this->getResponse(array('sugerencias' => $res));
	}
	
	public function generadorVentaAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array(), $this->getPlantilla('menu_izq'));
		$opciones['ajax'] = $this->getParametro('ajax');
		
		$infoClientes = $this->getParametro('cliente');
		$em = $this->getEm();
		$opciones['clientes'] = $em->getRepository($this->getEntidadLogico($infoClientes['repositorio']))->findAll();
		
		return $this->render($this->getPlantilla('editor'), $opciones);
	}
	
	public function plantillaExistenciaAction(Request $peticion){
		$opciones = array();
		$render = null;
		
		if($peticion->getMethod() == "POST"){
			$em = $this->getEm();
			$idExistencia = $peticion->request->get('id');
			$tipoExistencia = strtolower($peticion->request->get('tipo'));
			
			$infoExistencia = $this->getParametro('existencias');
			$existencia = $em->getRepository($infoExistencia[$tipoExistencia]['repositorio'])->find($idExistencia);
			
			if($existencia){
				$opciones['existencia'] = $existencia;
				$render = $this->render($this->getPlantilla('plantillaItem'), $opciones);
			}
		}
		
		return $render;
	}
}
?>