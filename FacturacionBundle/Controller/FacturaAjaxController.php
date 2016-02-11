<?php 
namespace RGM\eLibreria\FacturacionBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use RGM\eLibreria\FacturacionBundle\Entity\ClienteFacturacion;

class FacturaAjaxController extends Asistente{	
	private $bundle = "facturacionbundle";
	private $controlador = "facturaajax";
	
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
	
	public function getClientesAction(){
		$res = array();
		
		$em = $this->getEm();
		$repCli = $em->getRepository('RGMeLibreriaFacturacionBundle:ClienteFacturacion');
		
		$res = $repCli->findAll();
		
		return $this->getResponse($res);
	}
	
	public function getClienteAction(Request $peticion){
		$res = array(
				'success' => false
		);
		
		$idCliente = $peticion->request->get('idCliente');
		
		$em = $this->getEm();
		$repCli = $em->getRepository('RGMeLibreriaFacturacionBundle:ClienteFacturacion');
		
		$cliente = $repCli->find($idCliente);
		
		/*
		nif: 'NIF / CIF',
		razonSocial: 'Razón social',
		razonComercial: 'Razón comercial',
		dir: 'Dirección',
		cp: 'C.P.',
		tel: 'Tel.',
		email: 'Email',
		perCont: 'Persona de contacto',
		 */
		
		if($cliente){
			$res['cliente'] = array(
					'id' => $cliente->getId(),
					'nif' => $cliente->getCif(),
					'razonSocial' => $cliente->getRazonSocial(),
					'razonComercial' => $cliente->getRazonComercial(),
					'dir' => $cliente->getDireccion(),
					'cp' => $cliente->getCodigoPostal(),
					'tel' => $cliente->getTelefono(),
					'email' => $cliente->getEmail(),
					'perCont' => $cliente->getPersonaContacto(),		
			);
			
			$res['success'] = true;
		}
		
		return $this->getResponse($res);
	}
	
	public function updateClienteAction(Request $peticion){
		$res = array(
				'success' => false
		);
		
		if($peticion->isXmlHttpRequest()){
			$idCliente = $peticion->request->get('idCliente');
			$cliente;
			
			$em = $this->getEm();
			$repCli = $em->getRepository('RGMeLibreriaFacturacionBundle:ClienteFacturacion');

			$nif = $peticion->request->get('nif');
			$rC = $peticion->request->get('rC');
			$rS = $peticion->request->get('rS');
			$dir = $peticion->request->get('dir');
			$cp = $peticion->request->get('cp');
			$tel = $peticion->request->get('tel');
			$email = $peticion->request->get('email');
			$personaContacto = $peticion->request->get('personaContacto');
						
			$mod = false;
			
			if($idCliente){
				$cliente = $repCli->find($idCliente);
				
				if($cliente){
					$mod = true;
				}
			}
			else{
				$cliente = new ClienteFacturacion();
				$mod = true;
			}
			
			if($mod){
				$cliente->setCif($nif);
				$cliente->setRazonComercial($rC);
				$cliente->setRazonSocial($rS);
				$cliente->setDireccion($dir);
				$cliente->setCodigoPostal($cp);
				$cliente->setTelefono($tel);
				$cliente->setEmail($email);
				$cliente->setPersonaContacto($personaContacto);
				
				$em->persist($cliente);
				$em->flush();
				
				$res['success'] = true;
				$res['idCliente'] = $cliente->getId();
			}
		}
		
		return $this->getResponse($res);
	}
}
?>