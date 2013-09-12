<?php

namespace RGM\eLibreria\SuministroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Albaran
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Albaran {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="string", length=255)
	 * @ORM\Id
	 * 
	 * @GRID\Column(title="Numero Albaran")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ContratoSuministro", inversedBy="albaranes")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 * 
	 */
	private $contrato;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran", mappedBy="albaran")
	 */
	private $items;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaRealizacion", type="date", nullable=false)
	 * @GRID\Column(title="Fecha de Realizacion", format="d/m/Y")
	 */
	private $fechaRealizacion;

	/**
	 * @ORM\Column(name="total", type="float", nullable=true)
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $total;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaVencimiento", type="date", nullable=false)
	 * @GRID\Column(title="Fecha de Vencimiento", format="d/m/Y")
	 */
	private $fechaVencimiento;

	public function __construct() {
		$this->fechaRealizacion = new \DateTime();
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

	public function setId($i) {
		$this->id = $i;

		return $this;
	}

	/**
	 * Set contrato
	 *
	 * @param string $contrato
	 * @return Albaran
	 */
	public function setContrato($contrato) {
		$this->contrato = $contrato;

		return $this;
	}

	/**
	 * Get contrato
	 *
	 * @return string 
	 */
	public function getContrato() {
		return $this->contrato;
	}

	/**
	 * Set fechaRealizacion
	 *
	 * @param \DateTime $fechaRealizacion
	 * @return Albaran
	 */
	public function setFechaRealizacion($fechaRealizacion) {
		$this->fechaRealizacion = $fechaRealizacion;

		return $this;
	}

	/**
	 * Get fechaRealizacion
	 *
	 * @return \DateTime 
	 */
	public function getFechaRealizacion() {
		return $this->fechaRealizacion;
	}

	/**
	 * Set fechaVencimiento
	 *
	 * @param \DateTime $fechaVencimiento
	 * @return Albaran
	 */
	public function setFechaVencimiento($fechaVencimiento) {
		$this->fechaVencimiento = $fechaVencimiento;

		return $this;
	}

	/**
	 * Get fechaVencimiento
	 *
	 * @return \DateTime 
	 */
	public function getFechaVencimiento() {
		return $this->fechaVencimiento;
	}

	public function getItems() {
		return $this->items;
	}

	public function setItems($items) {
		$this->items = $items;

		return $this;
	}

	public function getBaseImponible() {
		$res = new ArrayCollection();

		foreach ($this->items as $i) {
			$elemento = $i->getElemento();
			$iva = $elemento->getIVA();
			$precio = $elemento->getPrecio();

			if ($res->containsKey($iva)) {
				$res[$iva] += $precio;
			} else {
				$res[$iva] = $precio;
			}
		}

		return $res;
	}

	public function getItemPorLibro($libro) {
		$res = array();

		foreach ($this->items as $i) {
			$ejemplar = $i->getEjemplar();

			if ($ejemplar) {
				$libroEjemplar = $ejemplar->getLibro();

				if ($libro->__equals($libroEjemplar)) {
					$res[] = $ejemplar;
				}
			}
		}

		return $res;
	}

	public function getTotal() {
		return $this->total;
	}

	public function setTotal($total) {
		$this->total = $total;
		
		return $this;
	}

}
