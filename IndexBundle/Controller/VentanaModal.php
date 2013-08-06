<?php 
	namespace RGM\eLibreria\IndexBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	
	class VentanaModal extends Controller{
		private $opciones;
		private $path;
		
		public function __construct($p, $o){						
			$this -> path = $p;
			$this -> opciones = $o;
		}
		
		private function formatearOpciones(){
			if(!isset($this -> opciones['msg']))
				$this -> opciones['msg'] = null;
		
			if(!isset($this -> opciones['titulo_form']))
				$this -> opciones['titulo_form'] = null;
		
			if(!isset($this -> opciones['form'])){
				$this -> opciones['form'] = null;
			}
			else{
				$this -> opciones['form'] = $this -> opciones['form'] -> createView();				
			}
		
			if(!isset($this -> opciones['form_manual'])){
				$this -> opciones['form_manual'] = null;
			}
			else{
				$this -> opciones['form_manual'] = $this -> opciones['form_manual'] -> createView();
			}
		
			if(!isset($this -> opciones['msg_confirmar']))
				$this -> opciones['msg_confirmar'] = null;
		
			if(!isset($this -> opciones['msg_confirmar2']))
				$this -> opciones['msg_confirmar2'] = null;
		
			if(!isset($this -> opciones['titulo_submit']))
				$this -> opciones['titulo_submit'] = null;
		
			if(!isset($this -> opciones['path_form']))
				$this -> opciones['path_form'] = null;
			
			if(!isset($this -> opciones['opcion_salto_msg']))
				$this -> opciones['opcion_salto_msg'] = null;

		}
		
		public function renderVentanaModal($c){	
			$this -> formatearOpciones();
			
			return $c -> render($this -> path . 'cargarVentanaModal.html.twig', $this -> opciones);
		}
	}
?>