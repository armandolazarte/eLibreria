<?php

namespace RGM\eLibreria\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;

/**
 * Rol
 *
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="denominacion", message="Rol en uso")
 */
class Rol implements RoleInterface 
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
     * @var string
     *
     * @ORM\Column(name="denominacion", type="string", length=255, nullable=false)
     */
    private $denominacion;


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
     * Set denominacion
     *
     * @param string $denominacion
     * @return Rol
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
    
    public function getRole(){
    	return $this -> getDenominacion();
    }
}
