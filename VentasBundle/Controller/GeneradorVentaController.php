<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RGM\eLibreria\VentasBundle\Entity\DispensadorItemsVenta;

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
		else{
			$res = 'Sin Localización';
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
			
			$sql = 'SELECT e.id, e.localizacion_id, l.titulo, l.isbn, alb.numeroAlbaran, D.nombre FROM Existencia as e Natural Join existenciaLibro as eL, Libro as l, ItemAlbaran as iA, Albaran as alb, ContratoSuministro as cnSum, Distribuidora as D WHERE eL.libro_id = l.isbn AND e.vigente = 1 AND e.vendido = 0 AND iA.existencia_id = e.id AND iA.albaran_id = alb.id AND alb.contrato_id = cnSum.id AND D.id = cnSum.distribuidora_id AND l.isbn LIKE :ref LIMIT 20';
			
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
				$obj['distribuidora'] = $r['nombre'];
				$obj['numAlb'] = $r['numeroAlbaran'];
				
				$loc = '';				
				if($r['localizacion_id']){
					$loc = ' [' . $this->buscarLocalizacion($r['localizacion_id']) . ']';
				}
				$obj['loc'] = $loc;
				
				$res[] = $obj;
			}

			$sql = 'SELECT e.id, e.localizacion_id, a.titulo, a.ref, alb.numeroAlbaran, D.nombre FROM Existencia as e Natural Join existenciaArticulo as eA, Articulo as a, ItemAlbaran as iA, Albaran as alb, ContratoSuministro as cnSum, Distribuidora as D WHERE e.vendido = 0 AND e.vigente = 1 AND eA.articulo_id = a.id AND iA.existencia_id = e.id AND iA.albaran_id = alb.id AND alb.contrato_id = cnSum.id AND D.id = cnSum.distribuidora_id AND a.ref LIKE :ref LIMIT 20';
				
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
				$obj['distribuidora'] = $r['nombre'];
				$obj['numAlb'] = $r['numeroAlbaran'];
				
				$loc = '';				
				if($r['localizacion_id']){
					$loc = ' [' . $this->buscarLocalizacion($r['localizacion_id']) . ']';
				}
				$obj['loc'] = $loc;
				
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
				
			$sql = 'SELECT e.id, e.localizacion_id, l.titulo, l.isbn, alb.numeroAlbaran, D.nombre FROM Existencia as e Natural Join existenciaLibro as eL, Libro as l, ItemAlbaran as iA, Albaran as alb, ContratoSuministro as cnSum, Distribuidora as D WHERE eL.libro_id = l.isbn AND e.vigente = 1 AND e.vendido = 0 AND iA.existencia_id = e.id AND iA.albaran_id = alb.id AND alb.contrato_id = cnSum.id AND D.id = cnSum.distribuidora_id AND l.titulo LIKE :titulo LIMIT 20';
				
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
				$obj['distribuidora'] = $r['nombre'];
				$obj['numAlb'] = $r['numeroAlbaran'];
				
				$loc = '';				
				if($r['localizacion_id']){
					$loc = ' [' . $this->buscarLocalizacion($r['localizacion_id']) . ']';
				}
				$obj['loc'] = $loc;
			
				$res[] = $obj;
			}
			
			$sql = 'SELECT e.id, e.localizacion_id, a.titulo, a.ref, alb.numeroAlbaran, D.nombre FROM Existencia as e Natural Join existenciaArticulo as eA, Articulo as a, ItemAlbaran as iA, Albaran as alb, ContratoSuministro as cnSum, Distribuidora as D WHERE e.vendido = 0 AND e.vigente = 1 AND eA.articulo_id = a.id AND iA.existencia_id = e.id AND iA.albaran_id = alb.id AND alb.contrato_id = cnSum.id AND D.id = cnSum.distribuidora_id AND a.titulo LIKE :titulo LIMIT 20';
			
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
				$obj['distribuidora'] = $r['nombre'];
				$obj['numAlb'] = $r['numeroAlbaran'];
				
				$loc = '';				
				if($r['localizacion_id']){
					$loc = ' [' . $this->buscarLocalizacion($r['localizacion_id']) . ']';
				}
				$obj['loc'] = $loc;
			
				$res[] = $obj;
			}
		}
		
		return $this->getResponse(array('sugerencias' => $res));
	}
	
	public function generadorVentaAction(Request $peticion, $id = null){
		$opciones = $this->getArrayOpcionesVista(array(), $this->getPlantilla('menu_izq'));
		
		if($id){
			$em = $this->getEm();
			$infoEntidad = $this->getParametro('entidad');
			$venta = $em->getRepository($this->getEntidadLogico($infoEntidad['repositorio']))->find($id);
			
			if(!$venta){
				return $this->irInicio();
			}
			
			$existencias = array();
			
			foreach($venta->getItems() as $item){
				$ex = $item->getExistencia();
				$eArray = array();
				
				$eArray['id'] = $ex->getId();				
				$eArray['tipo'] = $ex->getTipo();
				
				$existencias[] = $eArray;
			}
			
			$vArray = array();
			
			$vArray['id'] = $venta->getId();
			$vArray['existencias'] = $existencias;
			
			$opciones['venta'] = json_encode($vArray);
		}
		
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
	
	public function borrarExistenciaAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$em = $this->getEm();
			$idExistencia = $peticion->request->get('id');
			$tipoExistencia = strtolower($peticion->request->get('tipo'));
			
			$infoExistencia = $this->getParametro('existencias');
			$existencia = $em->getRepository($infoExistencia[$tipoExistencia]['repositorio'])->find($idExistencia);
			
			if($existencia){
				$itemVenta = $existencia->getItemVenta();
				
				if($itemVenta){
					$em->remove($itemVenta);
				}
				
				$existencia->setVendido(0);
				$em->persist($existencia);
				
				$em->flush();
				$res['estado'] = true;
			}
		}
		
		return $this->getResponse($res);
	}
	
	public function registrarVentaAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$idVenta = $peticion->request->get('idVenta');
			$total = $peticion->request->get('total');
			$clienteId = $peticion->request->get('cliente');
			$metodoPago = $peticion->request->get('metodoPago');
			$itemsNuevos = $peticion->request->get('items');
			
			$infoVenta = $this->getParametro('entidad');
			$em = $this->getEm();
			
			if($idVenta == ""){
				$venta = $this->getNuevaInstancia($infoVenta['clase']);
				$venta->setFecha(new \DateTime('now'));
			}
			else{
				$venta = $em->getRepository($this->getEntidadLogico($infoVenta['repositorio']))->find($idVenta);
			}
			
			$venta->setMetodoPago($metodoPago);
			$venta->setTotal($total);
			
			if($clienteId != ""){
				$infoCliente = $this->getParametro('cliente');
				$cliente = $em->getRepository($infoCliente['repositorio'])->find($clienteId);
				
				if($cliente){
					$venta->setCliente($cliente);
				}
			}
			
			$em->persist($venta);
			$em->flush();
			
			$itemsDisponibles = array();
						
			foreach($venta->getItems() as $itemVenta){
				$ex = $itemVenta->getExistencia();
				$ex->setVendido(0);
				$em->persist($ex);
				
				$itemVenta->setExistencia(null);
				$itemsDisponibles[] = $itemVenta;
			}
			
			$dispensador = new DispensadorItemsVenta($venta, $itemsDisponibles);
			
			foreach($itemsNuevos as $i){
				$itemVenta = $dispensador->getItem();
				$itemVenta->setDescuento($i['desc']);
				$itemVenta->setPrecioVenta($i['precioVenta']);
				
				$idExistencia = $i['id'];
				$tipoExistencia = strtolower($i['tipo']);
				
				$infoExistencia = $this->getParametro('existencias');
				$existencia = $em->getRepository($infoExistencia[$tipoExistencia]['repositorio'])->find($idExistencia);
				
				$existencia->setVendido(1);
				$itemVenta->setExistencia($existencia);

				$em->persist($itemVenta);
				$em->persist($existencia);				
			}
			
			$dispensador->terminarTransaccion($em);
			
			$em->flush();
			
			$res['idVenta'] = $venta->getId();
			$res['estado'] = true;
		}
		
		return $this->getResponse($res);
	}
	
	public function getRutaTicketAction(Request $peticion){
		$res = array();
		
		if($peticion->getMethod() == "POST"){
			$idVenta = $peticion->request->get('idVenta');
			
			if($idVenta != ""){
				$res['estado'] = true;
				$res['url'] = $this->generateUrl($this->getParametro('ruta_ticket'), array('idVenta' => $idVenta));
			}
		}
		
		return $this->getResponse($res);
	}
}
?>