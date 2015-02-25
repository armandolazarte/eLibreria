<?php

namespace RGM\eLibreria\VentasBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * OrdenVenta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class OrdenVenta {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * 
	 */
	private $id;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="anno", type="integer")
	 * 
	 */
	private $anno;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="mes", type="integer")
	 * 
	 */
	private $mes;

	/**
	 * @var integer
	 *
	 * @ORM\Column(name="numOrden", type="integer")
	 * 
	 */
	private $numOrden;
	
	public function __construct(){
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	public function getAnno(){
		return $this->anno;
	}

	public function getMes(){
		return $this->mes;
	}

	public function getNumOrden(){
		return $this->numOrden;		
	}

	public function setAnno($anno){
		$this->anno = $anno;
	}
	
	public function setMes($mes){
		$this->mes = $mes;
	}
	
	public function setNumOrden($numOrden){
		$this->numOrden = $numOrden;
	}
}
