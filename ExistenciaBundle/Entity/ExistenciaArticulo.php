<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use RGM\eLibreria\SuministroBundle\Entity\Articulo;

/**
 * ExistenciaArticulo
 *
 * @ORM\Table(name="existenciaArticulo")
 * @ORM\Entity
 */
class ExistenciaArticulo extends Existencia
{
    /**
     * @ORM\ManyToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="existencias")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $articulo;
    
	public function __construct(Articulo $a) {
		parent::__construct();

		$this->setArticulo($a);
	}

	public function getReferencia(){
		return $this->articulo->getRef();
	}

	public function getObjetoVinculado(){
		return $this->articulo;
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
