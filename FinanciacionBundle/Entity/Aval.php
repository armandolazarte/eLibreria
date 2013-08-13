<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Aval
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Aval {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Banco", inversedBy="avales")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $banco;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Distribuidora", mappedBy="aval")
	 */
	private $distribuidoras;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="cantidad", type="float")
	 */
	private $cantidad;

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

	public function __construct(Banco $b) {
		$this->banco = $b;
		$this->distribuidoras = new ArrayCollection();
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
	 * Set banco
	 *
	 * @param string $banco
	 * @return Aval
	 */
	public function setBanco($banco) {
		$this->banco = $banco;

		return $this;
	}

	/**
	 * Get banco
	 *
	 * @return string 
	 */
	public function getBanco() {
		return $this->banco;
	}

	/**
	 * Set cantidad
	 *
	 * @param float $cantidad
	 * @return Aval
	 */
	public function setCantidad($cantidad) {
		$this->cantidad = $cantidad;

		return $this;
	}

	/**
	 * Get cantidad
	 *
	 * @return float 
	 */
	public function getCantidad() {
		return $this->cantidad;
	}

	/**
	 * Set fechaAlta
	 *
	 * @param \DateTime $fechaAlta
	 * @return Aval
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
	 * @return Aval
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
	 * Set contrato
	 *
	 * @param string $contrato
	 * @return Aval
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

	public function getDistribuidoras() {
		return $this->distribuidoras;
	}

	public function setDistribuidoras($distribuidoras) {
		$this->distribuidoras = $distribuidoras;

		return $this;
	}

}
