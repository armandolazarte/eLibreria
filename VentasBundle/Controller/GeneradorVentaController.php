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
	
	public function generadorVentaAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array(), $this->getPlantilla('menu_izq'));
		$opciones['ajax'] = $this->getParametro('ajax');
		
		$infoClientes = $this->getParametro('cliente');
		$em = $this->getEm();
		$opciones['clientes'] = $em->getRepository($this->getEntidadLogico($infoClientes['repositorio']))->findAll();
		
		return $this->render($this->getPlantilla('editor'), $opciones);
	}
}
?>