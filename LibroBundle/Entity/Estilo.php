<?php

namespace RGM\eLibreria\LibroBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Estilo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RGM\eLibreria\LibroBundle\Entity\RepositorioEstilo")
 * @DoctrineAssert\UniqueEntity(fields="denominacion", message="La denominacion ya esta en uso")
 */
class Estilo {
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
	 * @ORM\Column(name="denominacion", type="string", length=255, nullable = false)
	 * 
	 * @GRID\Column(title="Denominacion")
	 */
	private $denominacion;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Estilo", inversedBy="hijos")
	 */
	private $padre;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Estilo", mappedBy="padre")
	 */
	private $hijos;

	/**
	 * @ORM\ManyToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", mappedBy="estilos")
	 */
	private $libros;

	public function __construct() {
		$this->libros = new ArrayCollection();
		$this->hijos = new ArrayCollection();
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
	 * Set denominacion
	 *
	 * @param string $denominacion
	 * @return Estilo
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

	public function getLibros() {
		return $this->libros;
	}

	public function setLibros($libros) {
		$this->libros = $libros;

		return $this;
	}

	public function __toString() {
		return $this->getDenominacion();
	}

	public function getPadre() {
		return $this->padre;
	}

	public function setPadre($padre) {
		$this->padre = $padre;

		return $this;
	}

	public function getHijos() {
		return $this->hijos;
	}

	public function setHijos($hijos) {
		$this->hijos = $hijos;

		return $this;
	}

}
