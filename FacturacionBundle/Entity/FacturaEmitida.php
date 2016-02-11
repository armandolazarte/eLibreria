<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * FacturaEmitida
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FacturaEmitida {
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
	 * @var ClienteFacturacion
	 * 
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\ClienteFacturacion", inversedBy="facturasEmitidas")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @GRID\Column(visible=false)
	 */ 
	private $cliente;

	/**
	 * @var MetodoPago
	 * 
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\MetodoPago")
	 * @ORM\JoinColumn(nullable=false)
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $metodoPago;

	/**
	 * @var ArrayCollection
	 * 
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\FechaVencimiento", mappedBy="factura")
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $fechasVencimiento;

	/**
	 * @var ItemFactura
	 * 
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\ItemFactura", mappedBy="factura")
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $itemsFactura;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="numDoc", type="string", length=255, nullable = false)
	 * 
	 * @GRID\Column(title="Num. Documento")
	 */
	private $numDocumento;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaEmision", type="date", nullable = false)
	 * 
	 * @GRID\Column(title="Fecha Emisión")
	 */
	private $fechaEmision;

	/**
	 * @var double
	 * 
	 * @ORM\Column(name="total", type="float", nullable = true)
	 * 
	 * @GRID\Column(title="Total")
	 */
	private $total;

	/**
	 * @var string
	 * 
	 * @ORM\Column(name="observaciones", type="text", nullable=true)
	 * 
	 * @GRID\Column(visible=false)
	 * 
	 */
	private $observaciones;

	public function __construct() {
		$this->itemsFactura = new ArrayCollection();
		$this->fechasVencimiento = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getCliente() {
		return $this->cliente;
	}

	public function setCliente(ClienteFacturacion $cliente) {
		$this->cliente = $cliente;
	}

	public function getMetodoPago() {
		return $this->metodoPago;
	}

	public function setMetodoPago(MetodoPago $metodoPago) {
		$this->metodoPago = $metodoPago;
	}

	public function getFechasVencimiento() {
		return $this->fechasVencimiento;
	}

	public function setFechasVencimiento(ArrayCollection $fechasVencimiento) {
		$this->fechasVencimiento = $fechasVencimiento;
	}

	public function getItemsFactura() {
		return $this->itemsFactura;
	}

	public function setItemsFactura(ItemFactura $itemsFactura) {
		$this->itemsFactura = $itemsFactura;
	}

	public function getNumDocumento() {
		return $this->numDocumento;
	}

	public function setNumDocumento($numDocumento) {
		$this->numDocumento = $numDocumento;
	}

	public function getFechaEmision() {
		return $this->fechaEmision;
	}

	public function setFechaEmision(\DateTime $fechaEmision) {
		$this->fechaEmision = $fechaEmision;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal($total) {
		$this->total = $total;
	}

	public function getObservaciones() {
		return $this->observaciones;
	}

	public function setObservaciones($observaciones) {
		$this->observaciones = $observaciones;
	}

}
