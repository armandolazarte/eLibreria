<?php
namespace RGM\eLibreria\SuministroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use RGM\eLibreria\SuministroBundle\Controller\ArticuloVendible;

/**
 * Ejemplar
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Articulo implements ArticuloVendible {

	/**
	 * @ORM\Column(name="ref", type="integer")
	 * @ORM\Id
	 */
	private $ref;
	
	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran", mappedBy="articulo")
	 */
	private $itemAlbaran;

	/**
	 * @ORM\Column(name="titulo", type="string", length=255, nullable = false)
	 */
	private $titulo;

	/**
	 * @ORM\Column(name="precio", type="float", nullable=false)
	 */
	private $precio;

	/**
	 * @ORM\Column(name="iva", type="float", nullable=false)
	 */
	private $iva;

	/**
	 * @ORM\Column(name="descuento", type="float", nullable=false)
	 */ 
	private $descuento;

	public function getDescuento() {
		return $this->descuento;
	}

	public function getReferencia() {
		return $this->ref;
	}
	public function getPrecio() {
		return $this->precio;
	}
	public function getIVA() {
		return $this->iva;
	}

	public function getRef() {
		return $this->ref;
	}

	public function setRef($ref) {
		$this->ref = $ref;

		return $this;
	}

	public function getTitulo() {
		return $this->titulo;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}

	public function setPrecio($precio) {
		$this->precio = $precio;
	}

	public function setIva($iva) {
		$this->iva = $iva;
	}

	public function setDescuento($descuento) {
		$this->descuento = $descuento;
	}
	public function getPrecioTotal() {
		return $this->getPrecio() * (1 + $this->getIVA());
	}
}

?>