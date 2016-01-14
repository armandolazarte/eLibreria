<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Existencia
 *
 * @ORM\Table()
 * @ORM\Entity
 
 * @ORM\DiscriminatorMap({"existenciaLibro" = "ExistenciaLibro", "existenciaArticulo" = "ExistenciaArticulo", "existenciaConcepto" = "ExistenciaConcepto"})
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
	 * @var float
	 *
	 * @ORM\Column(name="beneficio", type="float")
	 */
	private $beneficio;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="vendido", type="integer")
	 */
	private $vendido = 0;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="vigente", type="boolean")
	 */
	private $vigente = 1;

	/**
	 * @var boolean
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
	 * @param ItemAlbaran $itemAlbaran
	 * @return Existencia
	 */
	public function setItemAlbaran($itemAlbaran) {
		$this->itemAlbaran = $itemAlbaran;

		return $this;
	}

	/**
	 * Get itemAlbaran
	 *
	 * @return ItemAlbaran 
	 */
	public function getItemAlbaran() {
		return $this->itemAlbaran;
	}

	/**
	 * Set itemVenta
	 *
	 * @param ItemVenta $itemVenta
	 * @return Existencia
	 */
	public function setItemVenta($itemVenta) {
		$this->itemVenta = $itemVenta;

		return $this;
	}

	/**
	 * Get itemVenta
	 *
	 * @return ItemVenta 
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
	 * @return Localizacion 
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

	public function isVendido() {
		return $this->vendido == 1;
	}

	public function getAdquirido() {
		return $this->adquirido;
	}

	public function setAdquirido($adquirido) {
		$this->adquirido = $adquirido;

		return $this;
	}

	public function isAdquirido() {
		return $this->adquirido == 1;
	}

	public function getTipo() {
		$claseString = get_class($this);
		$claseString = explode('\\', $claseString);
		$claseString = array_pop($claseString);

		$tipo = '';
		if (strtolower($claseString) == 'existencialibro') {
			$tipo = 'libro';
		} else {
			$tipo = 'articulo';
		}

		return $tipo;
	}

	public function getBeneficio() {
		return $this->beneficio;
	}

	public function setBeneficio($beneficio) {
		$this->beneficio = $beneficio;
		return $this;
	}

	public function getPVP() {
		return round( ($this->precio * (1 + $this->beneficio)) * (1 + $this->iva), 2);
	}

	public function getVigente() {
		return $this->vigente;
	}

	public function setVigente($vigente) {
		$this->vigente = $vigente;
	}

	public function isVigente(){
		return $this->vigente == 1;
	}
	
	public function getDistribuidora(){
		return $this->getItemAlbaran()->getAlbaran()->getContrato()->getDistribuidora();
	}
	
	public function getNombreDistribuidora(){
		return $this->getDistribuidora()->getNombre();
	}

	public function getAlbaran() {
		return $this->itemAlbaran->getAlbaran();
	}
}
