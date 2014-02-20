<?php

namespace RGM\eLibreria\FinanciacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Pago
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Pago
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
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\FinanciacionBundle\Entity\GastoCorriente", inversedBy="pagos")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * 
     * @GRID\Column(title="Gasto corriente", field="gasto.denominacion")
     */
    private $gasto;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     * 
     * @GRID\Column(title="Fecha de la factura", format="d/m/Y")
     */
    private $fecha;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", nullable=false)
     * 
     * @GRID\Column(title="Valor de la factura")
     */
    private $valor;

    public function __construct(GastoCorriente $gasto){
    	$this->gasto = $gasto;
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
     * Set gasto
     *
     * @param string $gasto
     * @return Pago
     */
    public function setGasto($gasto)
    {
        $this->gasto = $gasto;
    
        return $this;
    }

    /**
     * Get gasto
     *
     * @return string 
     */
    public function getGasto()
    {
        return $this->gasto;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Pago
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    
        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime 
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set valor
     *
     * @param float $valor
     * @return Pago
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    
        return $this;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }
}
