<?php

namespace RGM\eLibreria\SuministroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ContratoSuministro
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ContratoSuministro {
	private $formasPago = array('Deposito', 'En efectivo');

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Distribuidora", inversedBy="contratos")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $distribuidora;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Albaran", mappedBy="contrato")
	 */
	private $albaranes;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="precioTotal", type="float")
	 */
	private $precioTotal;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaAlta", type="date")
	 */
	private $fechaAlta;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaBaja", type="date")
	 */
	private $fechaBaja;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="tipoPago", type="integer")
	 */
	private $tipoPago = 0;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="observaciones", type="text")
	 */
	private $observaciones;

	public function __construct() {
		$this->albaranes = new ArrayCollection();
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
	 * Set distribuidora
	 *
	 * @param string $distribuidora
	 * @return ContratoSuministro
	 */
	public function setDistribuidora($distribuidora) {
		$this->distribuidora = $distribuidora;

		return $this;
	}

	/**
	 * Get distribuidora
	 *
	 * @return string 
	 */
	public function getDistribuidora() {
		return $this->distribuidora;
	}

	/**
	 * Set precioTotal
	 *
	 * @param float $precioTotal
	 * @return ContratoSuministro
	 */
	public function setPrecioTotal($precioTotal) {
		$this->precioTotal = $precioTotal;

		return $this;
	}

	/**
	 * Get precioTotal
	 *
	 * @return float 
	 */
	public function getPrecioTotal() {
		return $this->precioTotal;
	}

	/**
	 * Set fechaAlta
	 *
	 * @param \DateTime $fechaAlta
	 * @return ContratoSuministro
	 */
	public function setFechaAlta($fechaAlta) {
		$this->fechaAlta = $fechaAlta;

		return $this;
	}

	/**
	 * Get fechaAlta
	 *
	 * @return \DateTime 
	 */
	public function getFechaAlta() {
		return $this->fechaAlta;
	}

	/**
	 * Set fechaBaja
	 *
	 * @param \DateTime $fechaBaja
	 * @return ContratoSuministro
	 */
	public function setFechaBaja($fechaBaja) {
		$this->fechaBaja = $fechaBaja;

		return $this;
	}

	/**
	 * Get fechaBaja
	 *
	 * @return \DateTime 
	 */
	public function getFechaBaja() {
		return $this->fechaBaja;
	}

	/**
	 * Set tipoPago
	 *
	 * @param integer $tipoPago
	 * @return ContratoSuministro
	 */
	public function setTipoPago($tipoPago) {
		$this->tipoPago = $tipoPago;

		return $this;
	}

	/**
	 * Get tipoPago
	 *
	 * @return integer 
	 */
	public function getTipoPago() {
		return $this->tipoPago;
	}
	
	public function getTipoPagoString(){
		return $this->formasPago[$this->tipoPago];
	}

	/**
	 * Set observaciones
	 *
	 * @param string $observaciones
	 * @return ContratoSuministro
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

	public function getAlbaranes() {
		return $this->albaranes;
	}

	public function setAlbaranes($albaranes) {
		$this->albaranes = $albaranes;
		
		return $this;
	}

}
