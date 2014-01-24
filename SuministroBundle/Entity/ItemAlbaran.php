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
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\ExistenciaBundle\Entity\Existencia", inversedBy="itemAlbaran")
	 * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
	 */
	private $existencia;

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

	public function getDescuento() {
		return $this->descuento;
	}

	public function setDescuento($descuento) {
		$this->descuento = $descuento;

		return $this;
	}

	public function getExistencia() {
		return $this->existencia;
	}

	public function setExistencia($existencia) {
		$this->existencia = $existencia;

		return $this;
	}

}
