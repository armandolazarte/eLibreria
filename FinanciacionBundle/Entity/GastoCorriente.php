<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * GastoCorriente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GastoCorriente {
	private $pagoEstableString = array('No', 'Si');
	
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
	 * @ORM\Column(name="denominacion", type="string", length=255)
	 * 
	 * @GRID\Column(title="Denominacion")
	 */
	private $denominacion;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="empresa", type="string", length=255)
	 * 
	 * @GRID\Column(title="Empresa contratada")
	 */
	private $empresa;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Banco", inversedBy="gastosDomiciliados")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 * 
	 * @GRID\Column(field="banco.nombre", title="Banco domiciliado")
	 */
	private $banco;
	
	/**
	 * @ORM\Column(name="estable", type="integer")
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $pagoEstable = 0;
	
	/**
	 * @ORM\Column(name="mensualidad", type="float", nullable=true)
	 * 
	 * @GRID\Column(visible=false)
	 */
	private $mensualidad;

	/**
	 * 
	 */
	private $pagos;

	public function __construct() {
		$this->pagos = new ArrayCollection();
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
	 * @return GastoCorriente
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
	 * Set denominacion
	 *
	 * @param string $denominacion
	 * @return GastoCorriente
	 */
	public function setDenominacion($denominacion) {
		$this->denominacion = $denominacion;

		return $this;
	}

	/**
	 * Get denominacion
	 *
	 * @return string 
	 */
	public function getDenominacion() {
		return $this->denominacion;
	}

	public function getPagos() {
		return $this->pagos;
	}

	public function setPagos($pagos) {
		$this->pagos = $pagos;

		return $this;
	}

	public function getEmpresa() {
		return $this->empresa;
	}

	public function setEmpresa($empresa) {
		$this->empresa = $empresa;

		return $this;
	}
	
	public function setEstable($e){
		$this->pagoEstable = $e;
		
		return $this;
	}
	
	public function getEstableString(){
		return $this->pagoEstableString[$this->pagoEstable];
	}
	
	public function isEstable(){
		return $this->pagoEstable == 1;
	}
	
	public function getMensualidad(){
		$res = '-';
		
		if($this->isEstable()){
			$res = $this->mensualidad;
		}
		
		return $res;
	}
	
	public function setMensualidad($m){
		$this->mensualidad = $m;
		
		return $this;
	}
	
	public function __toString(){
		return $this->denominacion;
	}

}
