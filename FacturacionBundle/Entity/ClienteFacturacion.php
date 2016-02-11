<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ClienteFacturacion
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ClienteFacturacion {
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
	 * @ORM\Column(name="cif", type="string", length=255, nullable = false)
	 *
	 * @GRID\Column(title="CIF / NIF")
	 */
	private $cif;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="razonSocial", type="string", length=255, nullable = false)
	 *
	 * @GRID\Column(title="Razón Social")
	 */
	private $razonSocial;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="razonComercial", type="string", length=255, nullable = true)
	 *
	 * @GRID\Column(title="Razón Comercial")
	 */
	private $razonComercial;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="direccion", type="text", nullable = true)
	 *
	 * @GRID\Column(visible=false)
	 */
	private $direccion;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="codigoPostal", type="integer")
	 *
	 * @GRID\Column(visible=false)
	 */
	private $codigoPostal;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="telefono", type="string", length=255, nullable = true)
	 *
	 * @GRID\Column(visible=false)
	 */
	private $telefono;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=255, nullable = true)
	 *
	 * @GRID\Column(visible=false)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="personaContacto", type="string", length=255, nullable = true)
	 *
	 * @GRID\Column(visible=false)
	 */
	private $personaContacto;

	/**
	 * @var ArrayCollection
	 *
	 * @ORM\OneToMany(targetEntity="RGM\eLibreria\FacturacionBundle\Entity\FacturaEmitida", mappedBy="cliente")
	 *
	 */
	private $facturasEmitidas;

	public function __construct() {
		$this->facturasEmitidas = new ArrayCollection();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getRazonSocial() {
		return $this->razonSocial;
	}

	public function setRazonSocial($razonSocial) {
		$this->razonSocial = $razonSocial;
	}

	public function getRazonComercial() {
		return $this->razonComercial;
	}

	public function setRazonComercial($razonComercial) {
		$this->razonComercial = $razonComercial;
	}

	public function getCif() {
		return $this->cif;
	}

	public function setCif($cif) {
		$this->cif = $cif;
	}

	public function getDireccion() {
		return $this->direccion;
	}

	public function setDireccion($direccion) {
		$this->direccion = $direccion;
	}

	public function getCodigoPostal() {
		return $this->codigoPostal;
	}

	public function setCodigoPostal($codigoPostal) {
		$this->codigoPostal = $codigoPostal;
	}

	public function getTelefono() {
		return $this->telefono;
	}

	public function setTelefono($telefono) {
		$this->telefono = $telefono;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getPersonaContacto() {
		return $this->personaContacto;
	}

	public function setPersonaContacto($personaContacto) {
		$this->personaContacto = $personaContacto;
	}

	public function __toString() {
		return $this->cif . " - " . $this->razonSocial;
	}

	public function getFacturasEmitidas() {
		return $this->facturasEmitidas;
	}

	public function setFacturasEmitidas($facturasEmitidas) {
		$this->facturasEmitidas = $facturasEmitidas;
	}

}
