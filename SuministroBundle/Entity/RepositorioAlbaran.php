<?php 
namespace RGM\eLibreria\SuministroBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RepositorioAlbaran extends EntityRepository
{
	public function findAll()
	{
		return $this->findBy(array(), array('id' => 'DESC'));
	}
}
?>