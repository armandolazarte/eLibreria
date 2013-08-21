<?php

namespace RGM\eLibreria\SuministroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Recargo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Recargo
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
     * @var float
     *
     * @ORM\Column(name="iva", type="float")
     * 
     * @GRID\Column(title="IVA", type="text")
     */
    private $iva;

    /**
     * @var float
     *
     * @ORM\Column(name="recargo", type="float")
     * 
     * @GRID\Column(title="Recargo de equivalencia", type="text")
     */
    private $recargo;


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
     * Set iva
     *
     * @param float $iva
     * @return Recargo
     */
    public function setIva($iva)
    {
        $this->iva = $iva;
    
        return $this;
    }

    /**
     * Get iva
     *
     * @return float 
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * Set recargo
     *
     * @param float $recargo
     * @return Recargo
     */
    public function setRecargo($recargo)
    {
        $this->recargo = $recargo;
    
        return $this;
    }

    /**
     * Get recargo
     *
     * @return float 
     */
    public function getRecargo()
    {
        return $this->recargo;
    }
}
