<?php

namespace RGM\eLibreria\UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use RGM\eLibreria\EmpresaBundle\Entity\Empleado;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Usuario
 *
 * @ORM\Table()
 * @ORM\Entity
 * @DoctrineAssert\UniqueEntity(fields="username", message="Nombre de Usuario en uso")
 */
class Usuario implements UserInterface, \Serializable
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
     * @ORM\OneToOne(targetEntity="RGM\eLibreria\EmpresaBundle\Entity\Empleado")
     * @ORM\JoinColumn(onDelete="CASCADE", nullable = false)
     */
    private $empleado;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable = false)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable = false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable = false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="RGM\eLibreria\UsuarioBundle\Entity\Rol")
     * @ORM\JoinTable(name="asignacion_usuario_roles",
     * 	joinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")},
     * 	inverseJoinColumns={@ORM\JoinColumn(name="rol_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    private $roles;
    
    public function __construct(Empleado $e){
    	$this -> empleado = $e;
    	$this -> roles = new ArrayCollection();
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
     * Set empleado
     *
     * @param string $empleado
     * @return Usuario
     */
    public function setEmpleado($empleado)
    {
        $this->empleado = $empleado;
    
        return $this;
    }

    /**
     * Get empleado
     *
     * @return string 
     */
    public function getEmpleado()
    {
        return $this->empleado;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Usuario
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return Usuario
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    
        return $this;
    }

    /**
     * Get roles
     *
     * @return string 
     */
    public function getRoles()
    {
        return $this -> roles -> toArray();
    }
    
    public function addRole(Rol $r){
    	return $this -> roles -> add($r);	
    }
    
    //Interfaz UserInterface    
    public function equals(
    		\Symfony\Component\Security\Core\User\UserInterface $usuario) {
    	$res = $this->getUsername() == $usuario->getUsername();
    
    	return $res;
    }
    
    public function eraseCredentials() {
    }
    
    //Interfaz Serializable    
    /**
     * Serializes the content of the current User object
     * @return string
     *
     * id username password salt roles empleado
     */
    public function serialize() {
    	return \json_encode(
    			array($this->id, $this -> empleado, $this->username, $this->password,
    					$this->salt, $this->roles));
    }
    
    /**
     * Unserializes the given string in the current User object
     * @param serialized
     */
    public function unserialize($serialized) {
    	list($this->id, $this -> empleado, $this->username, $this->password,
    			$this->salt, $this->roles) = \json_decode($serialized);
    }
}
