<?php

namespace RGM\eLibreria\VentasBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemVenta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemVenta {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\VentasBundle\Entity\Venta", inversedBy="items")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $venta;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\ExistenciaBundle\Entity\Existencia", inversedBy="itemVenta")
	 * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
	 */
	private $existencia;

	/**
	 * @ORM\Column(name="descuento", type="float", nullable=false)
	 */
	private $descuento = 0;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set venta
	 *
	 * @param string $venta
	 * @return ItemVenta
	 */
	public function setVenta($venta) {
		$this->venta = $venta;

		return $this;
	}

	/**
	 * Get venta
	 *
	 * @return string 
	 */
	public function getVenta() {
		return $this->venta;
	}

	/**
	 * Set descuento
	 *
	 * @param float $descuento
	 * @return ItemVenta
	 */
	public function setDescuento($descuento) {
		$this->descuento = $descuento;

		return $this;
	}

	/**
	 * Get descuento
	 *
	 * @return float 
	 */
	public function getDescuento() {
		return $this->descuento;
	}

	public function getExistencia() {
		return $this->existencia;
	}

	public function setExistencia($existencia) {
		$this->existencia = $existencia;

		return $this;
	}

}
