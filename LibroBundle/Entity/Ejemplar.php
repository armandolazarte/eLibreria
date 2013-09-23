<?php

namespace RGM\eLibreria\LibroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\FloatNode;
use RGM\eLibreria\SuministroBundle\Controller\ArticuloVendible;

/**
 * Ejemplar
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Ejemplar implements ArticuloVendible {
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
	 * @ORM\Column(name="vendido", type="integer")
	 */
	private $vendido = 0;

	/**
	 * @var string
	 *
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Localizacion")
	 * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
	 */
	private $localizacion;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran", mappedBy="ejemplar")
	 */
	private $itemAlbaran;

	private $vigente = 1;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="precio", type="float", nullable=false)
	 */
	private $precio;

	/**
	 * @var float
	 *
	 * @ORM\Column(name="iva", type="float", nullable=false)
	 */
	private $iva;

	public function __construct(Libro $l){
		$this->libro = $l;
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

	public function isVendido() {
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

	public function getPrecio() {
		return $this->precio;
	}

	public function setPrecio($precio) {
		$this->precio = $precio;
		return $this;
	}

	public function getIva() {
		return $this->iva;
	}

	public function setIva($IVA) {
		$this->iva = $IVA;
		
		return $this;
	}

	public function getPrecioTotal() {
		return $this->getPrecio() * (1 + $this->getIva());
	}

	public function getItemAlbaran() {
		return $this->itemAlbaran;
	}

	public function setItemAlbaran($itemAlbaran) {
		$this->itemAlbaran = $itemAlbaran;

		return $this;
	}

	public function getAlbaran() {
		return $this->itemAlbaran->getAlbaran();
	}

	public function getVigente() {
		return $this->vigente;
	}

	public function setVigente($vigente) {
		$this->vigente = $vigente;

		return $this;
	}

	public function getReferencia() {
		return $this->libro->getIsbn();
	}
	
	public function getTitulo() {
		return $this->getLibro()->getTitulo();
	}

}
