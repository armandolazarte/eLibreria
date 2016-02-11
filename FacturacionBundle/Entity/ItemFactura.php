<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemFactura
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemFactura {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 */
	private $id;

	/**
	 * @var FacturaEmitida
	 * 
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\FacturaEmitida", inversedBy="itemsFactura")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $factura;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="concepto", type="string", length=255, nullable = false)
	 */
	private $concepto;

	/**
	 * @var integer
	 * 
	 * @ORM\Column(name="cantidad", type="integer", nullable = false)
	 */
	private $cantidad;

	/**
	 * @var double
	 * 
	 * @ORM\Column(name="pUni", type="float", nullable = false)
	 */
	private $precioUnitario;

	/**
	 * @var double
	 * 
	 * @ORM\Column(name="iva", type="float", nullable = false)
	 */
	private $iva;

	/**
	 * @var double
	 * 
	 * @ORM\Column(name="desc", type="float", nullable = true)
	 */
	private $descuento;

	public function __construct(FacturaEmitida $f) {
		$this->factura = $f;
		$this->descuento = 0.0;
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getFactura() {
		return $this->factura;
	}

	public function setFactura(FacturaEmitida $factura) {
		$this->factura = $factura;
	}

	public function getConcepto() {
		return $this->concepto;
	}

	public function setConcepto($concepto) {
		$this->concepto = $concepto;
	}

	public function getCantidad() {
		return $this->cantidad;
	}

	public function setCantidad($cantidad) {
		$this->cantidad = $cantidad;
	}

	public function getPrecioUnitario() {
		return $this->precioUnitario;
	}

	public function setPrecioUnitario($precioUnitario) {
		$this->precioUnitario = $precioUnitario;
	}

	public function getIva() {
		return $this->iva;
	}

	public function setIva($iva) {
		$this->iva = $iva;
	}

	public function getDescuento() {
		return $this->descuento;
	}

	public function setDescuento($descuento) {
		$this->descuento = $descuento;
	}

}
