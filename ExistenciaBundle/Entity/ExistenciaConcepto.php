<?php

namespace RGM\eLibreria\ExistenciaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * ExistenciaConcepto
 *
 * @ORM\Table(name="existenciaConcepto")
 * @ORM\Entity
 */
class ExistenciaConcepto extends Existencia {
	/**
	 * @var string
	 *
	 * @ORM\Column(name="concepto", type="string", length=255, nullable = false)
	 */
	private $concepto;

	public function __construct($c) {
		parent::__construct();

		$this->concepto = $c;
	}

	public function getReferencia() {
		return $this->getId();
	}

	public function getObjetoVinculado() {
		return $this;
	}

	public function getConcepto() {
		return $this->concepto;
	}

	public function setConcepto($concepto) {
		$this->concepto = $concepto;
	}

	public function getTitulo(){
		return $this->getConcepto();
	}
	
	public function getNombreDistribuidora(){
		return 'Sin Distribuidora';
	}
}
