<?php

namespace RGM\eLibreria\SuministroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Distribuidora
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Distribuidora
{
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
     * @ORM\Column(name="nombre", type="string", length=255)
     * 
     * @GRID\Column(title="Distribuidora")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefonoContacto", type="string", length=255)
     * 
     * @GRID\Column(title="Telefono de contacto")
     */
    private $telefonoContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="text")
     * 
     * @GRID\Column(title="Direccion")
     */
    private $direccion;

    /**
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Aval", inversedBy="distribuidoras")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $aval;

    /**
     * @ORM\OneToMany(targetEntity="RGM\eLibreria\SuministroBundle\Entity\ContratoSuministro", mappedBy="distribuidora")
     */
    private $contratos;
    
    public function __construct(){
    	$this->contratos = new ArrayCollection();	
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param integer
     * @return Distribuidora 
     */
    public function setId($i)
    {
    	$this->id = $i;
        return $this;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Distribuidora
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     * @return Distribuidora
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;
    
        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string 
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     * @return Distribuidora
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string 
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set aval
     *
     * @param string $aval
     * @return Distribuidora
     */
    public function setAval($aval)
    {
        $this->aval = $aval;
    
        return $this;
    }

    /**
     * Get aval
     *
     * @return string 
     */
    public function getAval()
    {
        return $this->aval;
    }

    /**
     * Set contratos
     *
     * @param string $contratos
     * @return Distribuidora
     */
    public function setContratos($contratos)
    {
        $this->contratos = $contratos;
    
        return $this;
    }

    /**
     * Get contratos
     *
     * @return string 
     */
    public function getContratos()
    {
        return $this->contratos;
    }
    
    public function __toString(){
    	return $this->getNombre();
    }
}
