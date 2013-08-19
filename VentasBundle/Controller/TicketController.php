<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\AsistenteController;
use RGM\eLibreria\IndexBundle\Controller\GridController;

class TicketController{
	
	private $plantilla_ticket = 'RGMELibreriaVentasBundle:Ticket:verTicket.html.twig';
	
	public function __construct(){
		
	}
	
	public function verTicketAction(){
		return $this->render($this->plantilla_ticket, array());
	}
}
?>