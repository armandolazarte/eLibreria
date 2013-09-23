<?php

namespace RGM\eLibreria\ReservasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use APY\DataGridBundle\Grid\Mapping as GRID;

/**
 * Reserva
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Reserva
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
     * @ORM\Column(name="nombre_contacto", type="string", length=255, nullable=false)
     * 
     * @GRID\Column(title="Nombre")
     */
    private $nombreContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono_contacto", type="string", length=255, nullable=false)
     * 
     * @GRID\Column(title="Telefono")
     */
    private $telefonoContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="isbn", type="string", length=255, nullable=true)
     * 
     * @GRID\Column(title="ISBN")
     */
    private $isbn;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=true)
     * 
     * @GRID\Column(title="Titulo")
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="editorial", type="string", length=255, nullable=true)
     * 
     * @GRID\Column(title="Editorial")
     */
    private $editorial;

    /**
     * @var string
     *
     * @ORM\Column(name="autor", type="string", length=255, nullable=true)
     * 
     * @GRID\Column(title="Autor")
     */
    private $autor;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Distribuidora")
     * 
     * @GRID\Column(title="Distribuidora", field="distribuidora.nombre")
     */
    private $distribuidora;

    /**
     * @var string
     *
     * @ORM\Column(name="observaciones", type="text", nullable=true)
     * 
     * @GRID\Column(visible=false)
     */
    private $observaciones;


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
     * Set nombreContacto
     *
     * @param string $nombreContacto
     * @return Reserva
     */
    public function setNombreContacto($nombreContacto)
    {
        $this->nombreContacto = $nombreContacto;
    
        return $this;
    }

    /**
     * Get nombreContacto
     *
     * @return string 
     */
    public function getNombreContacto()
    {
        return $this->nombreContacto;
    }

    /**
     * Set telefonoContacto
     *
     * @param string $telefonoContacto
     * @return Reserva
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;
    
        return $this;
    }

    /**
     * Get telefonoContacto
     *
     * @return string 
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set isbn
     *
     * @param string $isbn
     * @return Reserva
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
     * Set titulo
     *
     * @param string $titulo
     * @return Reserva
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
     * Set editorial
     *
     * @param string $editorial
     * @return Reserva
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
     * @return Reserva
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;
    
        return $this;
    }

    /**
     * Get autor
     *
     * @return string 
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Set distribuidora
     *
     * @param string $distribuidora
     * @return Reserva
     */
    public function setDistribuidora($distribuidora)
    {
        $this->distribuidora = $distribuidora;
    
        return $this;
    }

    /**
     * Get distribuidora
     *
     * @return string 
     */
    public function getDistribuidora()
    {
        return $this->distribuidora;
    }

    /**
     * Set observaciones
     *
     * @param string $observaciones
     * @return Reserva
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;
    
        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string 
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }
}
