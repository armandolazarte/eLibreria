<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RGM\eLibreria\LibroBundle\Entity\Libro;

/**
 * ExistenciaLibro
 *
 * @ORM\Table(name="existenciaLibro")
 * @ORM\Entity
 */
class ExistenciaLibro extends Existencia
{

    /**
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Libro", inversedBy="existencias")
	 * @ORM\JoinColumn(referencedColumnName="isbn", nullable=false, onDelete="CASCADE")
     */
    private $libro;

    public function __construct(Libro $l){
    	parent::__construct();
    	
    	$this->setLibro($l);
    }

	public function getReferencia(){
		return $this->libro->getIsbn();
	}

	public function getObjetoVinculado(){
		return $this->libro;
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
