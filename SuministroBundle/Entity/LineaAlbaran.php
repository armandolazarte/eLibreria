<?php
namespace RGM\eLibreria\SuministroBundle\Entity;
class LineaAlbaran {
	private $ref;
	private $editorial;
	private $titulo;
	private $numeroUnidades;
	private $precio;
	private $iva;
	private $descuento;

	public function getRef() {
		return $this->ref;
	}

	public function setRef($r) {
		$this->ref = $r;

		return $this;
	}

	public function getEditorial() {
		return $this->editorial;
	}

	public function setEditorial($editorial) {
		$this->editorial = $editorial;

		return $this;
	}

	public function getTitulo() {
		return $this->titulo;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;

		return $this;
	}

	public function getNumeroUnidades() {
		return $this->numeroUnidades;
	}

	public function setNumeroUnidades($numeroUnidades) {
		$this->numeroUnidades = $numeroUnidades;

		return $this;
	}

	public function getPrecio() {
		return $this->precio;
	}

	public function setPrecio($precio) {
		$this->precio = $precio;

		return $this;
	}

	public function getIva() {
		return $this->iva;
	}

	public function setIva($iva) {
		$this->iva = $iva;

		return $this;
	}

	public function getDescuento() {
		return $this->descuento;
	}

	public function setDescuento($descuento) {
		$this->descuento = $descuento;

		return $this;
	}

}
?>