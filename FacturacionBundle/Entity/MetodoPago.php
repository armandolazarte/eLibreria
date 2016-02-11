<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * MetodoPago
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class MetodoPago {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="denominacion", type="string", length=255, nullable = false)
	 *
	 * @GRID\Column(title="Denominación")
	 */
	private $denominacion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="observaciones", type="text", nullable = true)
	 *
	 * @GRID\Column(title="Observaciones")
	 */
	private $observaciones;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function __toString() {
		return $this->denominacion;
	}

	public function getDenominacion() {
		return $this->denominacion;
	}

	public function setDenominacion($denominacion) {
		$this->denominacion = $denominacion;
	}

	public function getObservaciones() {
		return $this->observaciones;
	}

	public function setObservaciones($observaciones) {
		$this->observaciones = $observaciones;
	}

}
