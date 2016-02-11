<?php

namespace RGM\eLibreria\FacturacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * FacturaRecibida
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class FacturaRecibida
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString(){
    	return $this -> apellidos . ', ' . $this -> nombre;
    }
}
