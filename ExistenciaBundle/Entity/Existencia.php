<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Existencia
 *
 * @ORM\Table()
 * @ORM\Entity
 
 * @ORM\DiscriminatorMap({"existenciaLibro" = "ExistenciaLibro", "existenciaArticulo" = "ExistenciaArticulo"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="tipo", type="string")
 */

class Existencia {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="precio", type="float")
	 */
	private $precio;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="iva", type="float")
	 */
	private $iva;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="vendido", type="integer")
	 */
	private $vendido = 0;
 
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="adquirido", type="integer")
	 */
	private $adquirido = 0;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran", mappedBy="existencia")
	 */
	private $itemAlbaran;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\VentasBundle\Entity\ItemVenta", mappedBy="existencia")
	 */
	private $itemVenta;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Localizacion")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 */
	private $localizacion;

	public function __construct(){
		
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
	 * Set precio
	 *
	 * @param float $precio
	 * @return Existencia
	 */
	public function setPrecio($precio) {
		$this->precio = $precio;

		return $this;
	}

	/**
	 * Get precio
	 *
	 * @return float 
	 */
	public function getPrecio() {
		return $this->precio;
	}

	/**
	 * Set iva
	 *
	 * @param float $iva
	 * @return Existencia
	 */
	public function setIva($iva) {
		$this->iva = $iva;

		return $this;
	}

	/**
	 * Get iva
	 *
	 * @return float 
	 */
	public function getIva() {
		return $this->iva;
	}

	/**
	 * Set itemAlbaran
	 *
	 * @param string $itemAlbaran
	 * @return Existencia
	 */
	public function setItemAlbaran($itemAlbaran) {
		$this->itemAlbaran = $itemAlbaran;

		return $this;
	}

	/**
	 * Get itemAlbaran
	 *
	 * @return string 
	 */
	public function getItemAlbaran() {
		return $this->itemAlbaran;
	}

	/**
	 * Set itemVenta
	 *
	 * @param string $itemVenta
	 * @return Existencia
	 */
	public function setItemVenta($itemVenta) {
		$this->itemVenta = $itemVenta;

		return $this;
	}

	/**
	 * Get itemVenta
	 *
	 * @return string 
	 */
	public function getItemVenta() {
		return $this->itemVenta;
	}

	/**
	 * Set localizacion
	 *
	 * @param string $localizacion
	 * @return Existencia
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

	public function getVendido() {
		return $this->vendido;
	}

	public function setVendido($vendido) {
		$this->vendido = $vendido;
		
		return $this;
	}
	
	public function isVendido(){
		return $this->vendido = 1;
	}

	public function getAdquirido() {
		return $this->adquirido;
	}

	public function setAdquirido($adquirido) {
		$this->adquirido = $adquirido;
		
		return $this;
	}
	
	public function isAdquirido(){
		return $this->adquirido = 1;
	}
}
