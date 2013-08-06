<?php

namespace RGM\eLibreria\LibroBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Libro
 *
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="titulo", message="El titulo ya esta en uso")
 * 
 */
class Libro
{
    /**
     * @ORM\Id
     * @ORM\Column(name="isbn", type="string", length=255)
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Editorial", inversedBy="libros")
     * @ORM\JoinColumn(nullable = true, onDelete = "SET NULL")
     * 
     * @GRID\Column(field="editorial.nombre", title="Editorial")
     */
    private $editorial;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Autor", inversedBy="libros")
     * @ORM\JoinTable(name="autores_libro",
     * 		joinColumns={
     * 			@ORM\JoinColumn(name="libro_id", referencedColumnName="isbn", nullable = false, onDelete="CASCADE")
     * 		},
     * 		inverseJoinColumns={
     * 			@ORM\JoinColumn(name="autor_id", referencedColumnName="id", nullable = false, onDelete="CASCADE")
     * 		}
     * )
     * 
     */
    private $autores;

    /**
     * @ORM\ManyToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Estilo", inversedBy="libros")
     * @ORM\JoinTable(name="estilos_libro",
     * 		joinColumns={
     * 			@ORM\JoinColumn(name="libro_id", referencedColumnName="isbn", nullable = false, onDelete="CASCADE")
     * 		},
     * 		inverseJoinColumns={
     * 			@ORM\JoinColumn(name="estilo_id", referencedColumnName="id", nullable = false, onDelete="CASCADE")
     * 		}
     * )
     */
    private $estilos;

    /**
     * @ORM\OneToMany(targetEntity="RGM\eLibreria\LibroBundle\Entity\Ejemplar", mappedBy="libro")
     */
    private $ejemplares;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable = false)
     */
    private $titulo;

    /**
     * @var integer
     *
     * @ORM\Column(name="numPaginas", type="integer", nullable = true)
     */
    private $numPaginas;

    /**
     * @var string
     *
     * @ORM\Column(name="sinopsis", type="text", nullable = true)
     */
    private $sinopsis;

    public function __construct(){
    	$this -> autores = new ArrayCollection();
    	$this -> estilos = new ArrayCollection();
    	$this -> ejemplares = new ArrayCollection();
    }

    /**
     * Set isbn
     *
     * @param string $isbn
     * @return Libro
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    
        return $this;
    }

    /**
     * Get isbn
     *
     * @return string 
     */
    public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * Set editorial
     *
     * @param string $editorial
     * @return Libro
     */
    public function setEditorial($editorial)
    {
        $this->editorial = $editorial;
    
        return $this;
    }

    /**
     * Get editorial
     *
     * @return string 
     */
    public function getEditorial()
    {
        return $this->editorial;
    }

    /**
     * Set autor
     *
     * @param string $autor
     * @return Libro
     */
    public function setAutores($autores)
    {
        $this->autores = $autores;
    
        return $this;
    }

    /**
     * Get autor
     *
     * @return string 
     */
    public function getAutores()
    {
        return $this->autores;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Libro
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    
        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set numPaginas
     *
     * @param integer $numPaginas
     * @return Libro
     */
    public function setNumPaginas($numPaginas)
    {
        $this->numPaginas = $numPaginas;
    
        return $this;
    }

    /**
     * Get numPaginas
     *
     * @return integer 
     */
    public function getNumPaginas()
    {
        return $this->numPaginas;
    }

    /**
     * Set sinopsis
     *
     * @param string $sinopsis
     * @return Libro
     */
    public function setSinopsis($sinopsis)
    {
        $this->sinopsis = $sinopsis;
    
        return $this;
    }

    /**
     * Get sinopsis
     *
     * @return string 
     */
    public function getSinopsis()
    {
        return $this->sinopsis;
    }

    /**
     * Set estilos
     *
     * @param string $estilos
     * @return Libro
     */
    public function setEstilos($estilos)
    {
        $this->estilos = $estilos;
    
        return $this;
    }

    /**
     * Get estilos
     *
     * @return string 
     */
    public function getEstilos()
    {
        return $this->estilos;
    }

    /**
     * Set ejemplares
     *
     * @param string $ejemplares
     * @return Libro
     */
    public function setEjemplares($ejemplares)
    {
        $this->ejemplares = $ejemplares;
    
        return $this;
    }

    /**
     * Get ejemplares
     *
     * @return string 
     */
    public function getEjemplares()
    {
        return $this->ejemplares;
    }
    
    public function getEjemplaresOrdenado(Criterio $c){
    	/*  usort($this -> ejemplares, "nombre_funcion_ordenacion"
    	    function nombre_funcion_ordenacion( $clave1, $clave2 ){
    	    	return (strlen($clave1) > strlen($clave2)) ? True : False;
			}
    	*/
    }
    
    public function __toString(){
    	return $this -> getTitulo();
    }
}
