<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

interface ArticuloVendible{
	public function getReferencia();
	public function getTitulo();
	public function getVendido();
	public function getPrecio();
	public function getIVA();
	public function getPrecioTotal();
}
?>