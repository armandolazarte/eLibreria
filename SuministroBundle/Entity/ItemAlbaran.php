<?php

namespace RGM\eLibreria\SuministroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ItemAlbaran
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemAlbaran {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Albaran", inversedBy="items")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $albaran;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Ejemplar", inversedBy="itemAlbaran")
	 * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
	 */
	private $ejemplar;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="itemAlbaran")
	 * @ORM\JoinColumn(name="articulo_id", referencedColumnName="ref", nullable=true, onDelete="CASCADE")
	 */
	private $articulo;

	/**
	 * @ORM\Column(name="descuento", type="float", nullable=false)
	 */
	private $descuento;

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set albaran
	 *
	 * @param string $albaran
	 * @return ItemAlbaran
	 */
	public function setAlbaran($albaran) {
		$this->albaran = $albaran;

		return $this;
	}

	/**
	 * Get albaran
	 *
	 * @return string 
	 */
	public function getAlbaran() {
		return $this->albaran;
	}

	/**
	 * Set ejemplar
	 *
	 * @param string $ejemplar
	 * @return ItemAlbaran
	 */
	public function setEjemplar($ejemplar) {
		$this->ejemplar = $ejemplar;

		return $this;
	}

	/**
	 * Get ejemplar
	 *
	 * @return string 
	 */
	public function getEjemplar() {
		return $this->ejemplar;
	}

	/**
	 * Set articulo
	 *
	 * @param string $articulo
	 * @return ItemAlbaran
	 */
	public function setArticulo($articulo) {
		$this->articulo = $articulo;

		return $this;
	}

	/**
	 * Get articulo
	 *
	 * @return string 
	 */
	public function getArticulo() {
		return $this->articulo;
	}

	public function getElemento() {
		$res = null;

		if ($this->ejemplar) {
			$res = $this->ejemplar;
		}

		if ($this->articulo) {
			$res = $this->articulo;
		}

		return $res;
	}

	public function getDescuento() {
		return $this->descuento;
	}

	public function setDescuento($descuento) {
		$this->descuento = $descuento;

		return $this;
	}

}
