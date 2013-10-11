<?php

namespace RGM\eLibreria\UsuarioBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\Security\Core\SecurityContext;

class UsuarioController extends Asistente{
	private $bundle = "usuariobundle";
	private $controlador = "usuario";
		
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function editarAction(){
	}
	
	public function passwordAction(){
		
	}
	
	public function loginAction(){
		$peticion = $this->getRequest();
		$sesion = $peticion->getSession();
		
		$error = $peticion->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR,
				$sesion->get(SecurityContext::AUTHENTICATION_ERROR)
		);
		
		$opciones = array();
		$opciones['vm']['titulo'] = $this->getParametro('titulo_login');
		$opciones['vm']['plantilla'] = $this->getRecurso($this->getPlantilla('vm_login'));
		
		$opcionesVM = array();
		$opcionesVM['ruta_login'] = $this->generateUrl($this->getParametro('ruta_login'));
		$opcionesVM['last_username'] = $sesion->get(SecurityContext::LAST_USERNAME);
		$opcionesVM['error'] = $error;
		
		$opciones['vm']['opciones'] = $opcionesVM;
		
		return $this->render($this->getRecurso($this->getPlantilla('login')), $opciones);
	}
}
?>