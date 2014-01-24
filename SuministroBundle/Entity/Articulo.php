<?php
namespace RGM\eLibreria\SuministroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Ejemplar
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Articulo {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="bigint")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(name="ref", type="string", length=255, nullable = false)
	 */
	private $ref;

	/**
	 * @ORM\Column(name="titulo", type="string", length=255, nullable = false)
	 */
	private $titulo;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ArticuloContenido", mappedBy="padre")
	 */
	private $articulosContenidos;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ArticuloContenido", mappedBy="hijo")
	 */
	private $contenedor;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\ExistenciaBundle\Entity\ExistenciaArticulo", mappedBy="articulo")
	 */
	private $existencias;

	public function __construct() {
		$this->articulosContenidos = new ArrayCollection();
		$this->existencias = new ArrayCollection();
	}

	public function getId($id) {
		return $this->id;
	}

	public function getRef() {
		return $this->ref;
	}

	public function setRef($ref) {
		$this->ref = $ref;

		return $this;
	}

	public function getTitulo() {
		return $this->titulo;
	}

	public function setTitulo($titulo) {
		$this->titulo = $titulo;

		return $this;
	}

	public function getExistencias() {
		return $this->existencias;
	}

	public function setExistencias($existencias) {
		$this->existencias = $existencias;

		return $this;
	}

	public function getArticulosContenidos() {
		return $this->articulosContenidos;
	}

	public function setArticulosContenidos($articulosContenidos) {
		$this->articulosContenidos = $articulosContenidos;

		return $this;
	}

	public function getContenedor() {
		return $this->contenedor;
	}

	public function setContenedor($contenedor) {
		$this->contenedor = $contenedor;

		return $this;
	}

}
?>