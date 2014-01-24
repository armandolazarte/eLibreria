<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExistenciaLibro
 *
 * @ORM\Table(name="existenciaLibro")
 * @ORM\Entity
 */
class ExistenciaLibro extends Existencia
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
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", inversedBy="existencias")
	 * @ORM\JoinColumn(referencedColumnName="isbn", nullable=false, onDelete="CASCADE")
     */
    private $libro;

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
     * Set libro
     *
     * @param string $libro
     * @return ExistenciaLibro
     */
    public function setLibro($libro)
    {
        $this->libro = $libro;
    
        return $this;
    }

    /**
     * Get libro
     *
     * @return string 
     */
    public function getLibro()
    {
        return $this->libro;
    }
}
