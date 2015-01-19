<?php 
namespace RGM\eLibreria\SuministroBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RepositorioRecargo extends EntityRepository
{
	public function findRecargoIVA($iva)
	{
		return $this->findOneBy(array('iva' => $iva));
	}
}
?>