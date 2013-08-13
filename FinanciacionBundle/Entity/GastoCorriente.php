<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GastoCorriente
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class GastoCorriente
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
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\Banco", inversedBy="gastosDomiciliados")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $banco;

    /**
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=255)
     */
    private $denominacion;

    /**
     * @var string
     *
     * @ORM\Column(name="costeMes", type="string", length=255)
     */
    private $costeMes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="periodicidad", type="date")
     */
    private $periodicidad;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="date")
     */
    private $fechaAlta;


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
     * Set banco
     *
     * @param string $banco
     * @return GastoCorriente
     */
    public function setBanco($banco)
    {
        $this->banco = $banco;
    
        return $this;
    }

    /**
     * Get banco
     *
     * @return string 
     */
    public function getBanco()
    {
        return $this->banco;
    }

    /**
     * Set denominacion
     *
     * @param string $denominacion
     * @return GastoCorriente
     */
    public function setDenominacion($denominacion)
    {
        $this->denominacion = $denominacion;
    
        return $this;
    }

    /**
     * Get denominacion
     *
     * @return string 
     */
    public function getDenominacion()
    {
        return $this->denominacion;
    }

    /**
     * Set costeMes
     *
     * @param string $costeMes
     * @return GastoCorriente
     */
    public function setCosteMes($costeMes)
    {
        $this->costeMes = $costeMes;
    
        return $this;
    }

    /**
     * Get costeMes
     *
     * @return string 
     */
    public function getCosteMes()
    {
        return $this->costeMes;
    }

    /**
     * Set periodicidad
     *
     * @param \DateTime $periodicidad
     * @return GastoCorriente
     */
    public function setPeriodicidad($periodicidad)
    {
        $this->periodicidad = $periodicidad;
    
        return $this;
    }

    /**
     * Get periodicidad
     *
     * @return \DateTime 
     */
    public function getPeriodicidad()
    {
        return $this->periodicidad;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     * @return GastoCorriente
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;
    
        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime 
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }
}
