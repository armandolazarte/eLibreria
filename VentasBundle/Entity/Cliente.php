<?php

namespace RGM\eLibreria\VentasBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Cliente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cliente {
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
	 * @ORM\Column(name="nombreContacto", type="string", length=255, nullable=false)
	 * 
	 * @GRID\Column(title="Nombre de contacto")
	 */
	private $nombreContacto;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
	 * 
	 * @GRID\Column(title="Telefono")
	 */
	private $telefono;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="movil", type="string", length=255, nullable=true)
	 * 
	 * @GRID\Column(title="Movil")
	 */
	private $movil;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="direccion", type="text", nullable=true)
	 * 
	 * @GRID\Column(title="Dirección")
	 */
	private $direccion;

	/**
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\VentasBundle\Entity\Venta", mappedBy="cliente")
	 */
	private $compras;
	
	public function __construct(){
		$this->compras = new ArrayCollection();
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
	 * Set nombreContacto
	 *
	 * @param string $nombreContacto
	 * @return Cliente
	 */
	public function setNombreContacto($nombreContacto) {
		$this->nombreContacto = $nombreContacto;

		return $this;
	}

	/**
	 * Get nombreContacto
	 *
	 * @return string 
	 */
	public function getNombreContacto() {
		return $this->nombreContacto;
	}

	/**
	 * Set telefono
	 *
	 * @param string $telefono
	 * @return Cliente
	 */
	public function setTelefono($telefono) {
		$this->telefono = $telefono;

		return $this;
	}

	/**
	 * Get telefono
	 *
	 * @return string 
	 */
	public function getTelefono() {
		return $this->telefono;
	}

	/**
	 * Set movil
	 *
	 * @param string $movil
	 * @return Cliente
	 */
	public function setMovil($movil) {
		$this->movil = $movil;

		return $this;
	}

	/**
	 * Get movil
	 *
	 * @return string 
	 */
	public function getMovil() {
		return $this->movil;
	}

	/**
	 * Set direccion
	 *
	 * @param string $direccion
	 * @return Cliente
	 */
	public function setDireccion($direccion) {
		$this->direccion = $direccion;

		return $this;
	}

	/**
	 * Get direccion
	 *
	 * @return string 
	 */
	public function getDireccion() {
		return $this->direccion;
	}

	public function getCompras() {
		return $this->compras;
	}

	public function setCompras($compras) {
		$this->compras = $compras;

		return $this;
	}

}
