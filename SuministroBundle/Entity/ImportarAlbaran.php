<?php
namespace RGM\eLibreria\SuministroBundle\Entity;
class ImportarAlbaran {
	private $fichero;

	public function getFichero() {
		return $this->fichero;
	}

	public function setFichero($fichero) {
		$this->fichero = $fichero;
	}
}
?>