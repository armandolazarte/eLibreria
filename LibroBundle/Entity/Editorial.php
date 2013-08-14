<?php

namespace RGM\eLibreria\LibroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Doctrine\Common\Collections\ArrayCollection;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Editorial
 *
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="nombre", message="El nombre ya esta en uso")
 */
class Editorial
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable = false)
     * 
     * @GRID\Column(title="Nombre")
     */
    private $nombre;
    
    /**
     * 
     * @var unknown
     * 
     * @ORM\OneToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", mappedBy="editorial")
     */
    private $libros;

    
    public function __construct(){
    	$this -> libros = new ArrayCollection();
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
     * @return Editorial
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
    
    public function __toString(){
    	return $this -> getNombre();
    }
    
    public function getLibros(){
    	return $this -> libros;
    }
    
    public function setLibros($libros){
    	$this -> libros = $libros;
    	
    	return $this;
    }
}
