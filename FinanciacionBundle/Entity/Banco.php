<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Banco
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Banco
{
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;
    
    /**
     * @ORM\Column(name="cuenta", type="integer")
     */
    private $cuentaBancaria;
    
    /**
     * @ORM\OneToMany(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\GastoCorriente", mappedBy="banco")
     */
    private $gastosDomiciliados;

    /**
     * @ORM\OneToMany(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Aval", mappedBy="banco")
     */
    private $avales;

    public function __construct(){
    	$this->gastosDomiciliados = new ArrayCollection();
    	$this->avales = new ArrayCollection();
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
     * Set nombre
     *
     * @param string $nombre
     * @return Banco
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
     * Set avales
     *
     * @param string $avales
     * @return Banco
     */
    public function setAvales($avales)
    {
        $this->avales = $avales;
    
        return $this;
    }

    /**
     * Get avales
     *
     * @return string 
     */
    public function getAvales()
    {
        return $this->avales;
    }
}
