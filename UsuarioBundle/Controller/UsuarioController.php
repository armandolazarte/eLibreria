<?php

namespace RGM\eLibreria\UsuarioBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use Symfony\Component\Security\Core\SecurityContext;

class UsuarioController extends AsistenteController
{
	private $plantilla_login = 'login.html.twig';
	private $plantilla_editar = 'index.html.twig';
	private $plantilla_cambiar_password = 'password.html.twig';
	
	public function __construct(){
		parent::__construct(
				'rgarcia_entrelineas_usuario_homepage', 
				'RGMELibreriaUsuarioBundle', 
				'Usuario:');
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

		$opciones['titulo'] = $this -> getTitulo();
		$opciones['titulo_ventana'] = 'Login';
		$opciones['last_username'] = $sesion->get(SecurityContext::LAST_USERNAME);
		$opciones['error'] = $error;
		
		return $this->render($this -> getPath() . $this -> plantilla_login, $opciones);
	}
}
?>