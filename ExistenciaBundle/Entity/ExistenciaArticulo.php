<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExistenciaArticulo
 *
 * @ORM\Table(name="existenciaArticulo")
 * @ORM\Entity
 */
class ExistenciaArticulo extends Existencia
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
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="existencias")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $articulo;

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
     * Set articulo
     *
     * @param string $articulo
     * @return ExistenciaArticulo
     */
    public function setArticulo($articulo)
    {
        $this->articulo = $articulo;
    
        return $this;
    }

    /**
     * Get articulo
     *
     * @return string 
     */
    public function getArticulo()
    {
        return $this->articulo;
    }
}
