<?php

namespace RGM\eLibreria\LibroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\FloatNode;

/**
 * Ejemplar
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Ejemplar {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", inversedBy="ejemplares")
	 * @ORM\JoinColumn(referencedColumnName="isbn", nullable=false, onDelete="CASCADE")
	 */
	private $libro;

	/**
	 * @var boolean
	 *
	 * @ORM\Column(name="vendido", type="boolean")
	 */
	private $vendido = 0;

	/**
	 * @var string
	 *
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Localizacion")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 */
	private $localizacion;

	private $itemAlbaran;

	private $vigente = 1;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="precioSinIVA", type="float", nullable=false)
	 */
	private $precioSinIVA;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="IVA", type="float", nullable=false)
	 */
	private $IVA;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set libro
	 *
	 * @param string $libro
	 * @return Ejemplar
	 */
	public function setLibro($libro) {
		$this->libro = $libro;

		return $this;
	}

	/**
	 * Get libro
	 *
	 * @return string 
	 */
	public function getLibro() {
		return $this->libro;
	}

	/**
	 * Set vendido
	 *
	 * @param boolean $vendido
	 * @return Ejemplar
	 */
	public function setVendido($vendido) {
		$this->vendido = $vendido;

		return $this;
	}

	/**
	 * Get vendido
	 *
	 * @return boolean 
	 */
	public function getVendido() {
		return $this->vendido;
	}
	
	public function isVendido(){
		return $this->vendido == 1;
	}

	public function getVendidoString() {
		$res = "En Stock";

		if ($this->vendido) {
			$res = "Vendido";
		}

		return $res;
	}

	/**
	 * Set localizacion
	 *
	 * @param string $localizacion
	 * @return Ejemplar
	 */
	public function setLocalizacion($localizacion) {
		$this->localizacion = $localizacion;

		return $this;
	}

	/**
	 * Get localizacion
	 *
	 * @return string 
	 */
	public function getLocalizacion() {
		return $this->localizacion;
	}

	public function getPrecioSinIVA() {
		return $this->precioSinIVA;
	}

	public function setPrecioSinIVA($precioSinIVA) {
		$this->precioSinIVA = $precioSinIVA;
		return $this;
	}

	public function getIVA() {
		return $this->IVA;
	}

	public function setIVA($IVA) {
		$this->IVA = $IVA;
		return $this;
	}

	public function getPrecio() {
		return $this->precioSinIVA * (1 + $this->IVA);
	}

	public function getRecargoIVA() {
		$res = 0;

		if ($this->IVA <= 0.04) {
			$res = 0.005;
		}

		if ($this->IVA > 0.04 && $this->IVA <= 0.1) {
			$res = 0.014;
		}

		if ($this->IVA >= 0.21) {
			$res = 0.052;
		}

		return $res;
	}

	public function getImporte() {

		$iva = $this->precioSinIVA * $this->IVA;
		$rec = $this->precioSinIVA * $this->getRecargoIVA();
		$des = $this->precioSinIVA * $this->itemAlbaran->getDescuento();

		$res = $this->precioSinIVA + $iva + $rec - $des;

		return $res;
	}

	public function getItemAlbaran() {
		return $this->itemAlbaran;
	}

	public function setItemAlbaran($itemAlbaran) {
		$this->itemAlbaran = $itemAlbaran;
		
		return $this;
	}
	
	public function getAlbaran(){		
		return $this -> itemAlbaran -> getAlbaran();
	}
	
	public function getVigente() {
		return $this->vigente;
	}

	public function setVigente($vigente) {
		$this->vigente = $vigente;
		
		return $this;
	}

}
