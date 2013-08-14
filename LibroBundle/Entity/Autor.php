<?php

namespace RGM\eLibreria\LibroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Autor
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Autor
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
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255, nullable = false)
     * 
     * @GRID\Column(title="Apellidos")
     */
    private $apellidos;
    
    /**
     * @ORM\ManyToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", mappedBy="autores")
     */
    private $libros;


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
     * @return Autor
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Autor
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    
        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }
    
    public function getLibros(){
    	return $this -> libros;
    }
    
    public function setLibros($libros){
    	$this -> libros = $libros;
    	
    	return $this;
    }
    
    public function __toString(){
    	return $this -> apellidos . ', ' . $this -> nombre;
    }
}
