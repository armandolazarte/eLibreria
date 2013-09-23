<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

interface ArticuloVendible{
	public function getReferencia();
	public function getTitulo();
	public function getVendido();
	public function getPrecio();
	public function getIva();
	public function getPrecioTotal();
}
?>