<?php

namespace RGM\eLibreria\VentasBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Venta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RGM\eLibreria\VentasBundle\Entity\RepositorioVentas")
 */
class Venta {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 * @GRID\Column(title="Nº de Venta")
	 */
	private $id;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fecha", type="datetime", nullable=false)
	 * 
	 * @GRID\Column(title="Fecha", format="d/m/Y")
	 */
	private $fecha;

	/**
	 * @ORM\Column(name="total", type="float", nullable=true)
	 * 
	 * @GRID\Column(title="Total", type="text")
	 */
	private $total;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="observaciones", type="text", nullable=true)
	 * 
	 * @GRID\Column(title="Observaciones")
	 */
	private $observaciones;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\VentasBundle\Entity\ItemVenta", mappedBy="venta")
	 */
	private $items;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\VentasBundle\Entity\Cliente", inversedBy="compras")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 */
	private $cliente;

	/**
	 * @ORM\Column(name="metodoPago", type="integer")
	 * 
	 * @GRID\Column(title="Metodo de pago", filter="select", selectFrom="values", values={"1"="Efectivo", "2"="Tarjeta"})
	 */
	private $metodoPago = 1;

	public function __construct() {
		$this->items = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set fecha
	 *
	 * @param \DateTime $fecha
	 * @return Venta
	 */
	public function setFecha($fecha) {
		$this->fecha = $fecha;

		return $this;
	}

	/**
	 * Get fecha
	 *
	 * @return \DateTime 
	 */
	public function getFecha() {
		return $this->fecha;
	}

	/**
	 * Set observaciones
	 *
	 * @param string $observaciones
	 * @return Venta
	 */
	public function setObservaciones($observaciones) {
		$this->observaciones = $observaciones;

		return $this;
	}

	/**
	 * Get observaciones
	 *
	 * @return string 
	 */
	public function getObservaciones() {
		return $this->observaciones;
	}

	public function getItems() {
		return $this->items;
	}

	public function setItems($items) {
		$this->items = $items;

		return $this;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal($total) {
		$this->total = $total;

		return $this;
	}

	public function getCliente() {
		return $this->cliente;
	}

	public function setCliente($cliente) {
		$this->cliente = $cliente;

		return $this;
	}

	public function getMetodoPago() {
		return $this->metodoPago;
	}

	public function setMetodoPago($metodoPago) {
		$this->metodoPago = $metodoPago;

		return $this;
	}

}
