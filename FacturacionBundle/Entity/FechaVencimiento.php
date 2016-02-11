<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * FechaVencimiento
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FechaVencimiento {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fecha", type="date", nullable=false)
	 */
	private $fecha;

	/**
	 * @var double
	 * 
	 * @ORM\Column(name="cuantia", type="float", nullable=false)
	 */
	private $cuantia;

	/**
	 * @var FacturaEmitida
	 * 
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\FacturaEmitida", inversedBy="fechasVencimiento")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $factura;

	public function __construct(FacturaEmitida $f) {
		$this->factura = $f;
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getFecha() {
		return $this->fecha;
	}

	public function setFecha(\DateTime $fecha) {
		$this->fecha = $fecha;
	}

	public function getCuantia() {
		return $this->cuantia;
	}

	public function setCuantia($cuantia) {
		$this->cuantia = $cuantia;
	}

	public function getFactura() {
		return $this->factura;
	}

	public function setFactura(FacturaEmitida $factura) {
		$this->factura = $factura;
	}
}
