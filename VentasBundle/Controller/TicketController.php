<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;

class TicketController extends Asistente{
	private $bundle = 'ventasbundle';
	private $controlador = 'ticket';
	
	private $plantilla_ticket = 'RGMELibreriaVentasBundle:Ticket:verTicket.html.twig';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function verTicketAction(){
		return $this->render($this->plantilla_ticket, array());
	}
}
?>