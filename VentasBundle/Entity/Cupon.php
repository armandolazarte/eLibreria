<?php

namespace RGM\eLibreria\VentasBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cupon
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cupon {
	
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
	 * @ORM\Column(name="codigo", type="string", length=255, nullable=false)
	 * 
	 * @GRID\Column(title="Código")
	 */
	private $codigo;

	/**
	 *
	 * @ORM\Column(name="valor", type="float")
	 * 
	 * @GRID\Column(title="Valor", type="text")
	 */
	private $valor;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="fecha", type="datetime", nullable=false)
	 * 
	 * @GRID\Column(title="Fecha de Creación", format="d/m/Y")
	 */
	private $fechaCreacion;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="periodo", type="integer")
	 *
	 * @GRID\Column(title="Fecha de Vencimiento", type="text")
	 */
	private $periodoValidez;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="vigente", type="boolean")
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $vigente = 1;

	public function __construct() {
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getCodigo() {
		return $this->codigo;
	}

	public function setCodigo($codigo) {
		$this->codigo = $codigo;
	}

	public function getValor() {
		return $this->valor;
	}

	public function setValor($valor) {
		$this->valor = $valor;
	}

	public function getFechaCreacion() {
		return $this->fechaCreacion;
	}

	public function setFechaCreacion(\DateTime $fechaCreacion) {
		$this->fechaCreacion = $fechaCreacion;
	}

	public function getPeriodoValidez() {
		return $this->periodoValidez;
	}

	public function setPeriodoValidez($periodoValidez) {
		$this->periodoValidez = $periodoValidez;
	}

	public function setVigente($vigente) {
		$this->vigente = $vigente;
	}

	public function isVigente(){
		return $this->vigente == 1;
	}
	
}
