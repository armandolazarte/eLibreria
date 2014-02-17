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
	
	public function verTicketAction($idVenta){
		$em = $this->getEm();
		$infoVenta = $this->getParametro('venta');
		
		$venta = $em->getRepository($infoVenta['repositorio'])->find($idVenta);
		
		$baseTotal = 0;
		$productos = array();
		
		foreach($venta->getItems() as $item){
			$ex = $item->getExistencia();			
			$prod = $ex->getObjetoVinculado();
			$id = $ex->getReferencia();
			
			$linea = array();
			
			if(!array_key_exists($id, $productos)){
				$productos[$id]['cant'] = 1;
				$productos[$id]['titulo'] = $prod->getTitulo();
				$productos[$id]['pf'] = $item->getPrecioVenta();
				$descuento = $item->getDescuento();

				if($descuento > 0){
					$productos[$id]['titulo_desc'] = 'Descuento aplicado: ' . $descuento . '%';
					$productos[$id]['pvp'] = $ex->getPVP() . '€';
				}
			}
			else{
				$productos[$id]['cant']++;
			}
		}
		
		foreach ($productos as $p){
			$baseTotal += round($p['pf'] * $p['cant'], 2);
		}
		
		return $this->render($this->plantilla_ticket, array('venta' => $venta, 'productos' => $productos, 'base' => $baseTotal));
	}
}
?>