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
class ArticuloContenido {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="bigint")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="articulosContenidos")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $padre;

	/**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="contenedor")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
	private $hijo;

	/**
	 * @ORM\Column(name="unidades", type="integer")
	 */
	private $unidades = 1;

	public function __construct(Articulo $padre, Articulo $hijo) {
		$this->setPadre($padre);
		$this->setHijo($hijo);
	}

	public function getId($id) {
		return $this->id;
	}

	public function getPadre() {
		return $this->padre;
	}

	public function setPadre($padre) {
		$this->padre = $padre;
	}

	public function getHijo() {
		return $this->hijo;
	}

	public function setHijo($hijo) {
		$this->hijo = $hijo;
	}

	public function getUnidades() {
		return $this->unidades;
	}

	public function setUnidades($unidades) {
		$this->unidades = $unidades;
	}

}
?>