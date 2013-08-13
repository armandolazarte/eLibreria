<?php

namespace RGM\eLibreria\IndexBundle\Controller;

class IndexController extends AsistenteController
{
	private $plantilla_verDatos = 'index.html.twig';
	
	public function __construct(){
		parent::__construct(
				'rgarcia_entrelineas_index_homepage', 
				'RGMELibreriaIndexBundle',
				'Libreria Entre Lineas',
				'Inicio');
		
		$this->setPath('Index:');
	}
	
    public function indexAction()
    {
    	$opciones = $this -> getOpcionesPlantilla();
    	
        return $this->render($this -> getPath() . $this -> plantilla_verDatos, $opciones);
    }
}
