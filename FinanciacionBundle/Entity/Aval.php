<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

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
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Banco", inversedBy="avales")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 * 
	 * @GRID\Column(field="banco.nombre", title="Banco")
	 */
	private $banco;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Distribuidora", mappedBy="aval")
	 */
	private $distribuidoras;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="cantidad", type="float", nullable=false)
	 * 
	 * @GRID\Column(title="Cuantia")
	 */
	private $cantidad;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fechaAlta", type="date", nullable=false)
	 * 
	 * @GRID\Column(title="Fecha de Realizacion", format="d/m/Y")
	 */
	private $fechaAlta;

	public function __construct(){
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
	
	public function getFechaDevolucion(){
		$res = clone $this->fechaAlta;
		$res->modify('+1 year');
		
		return $res->format('d/m/Y');
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
