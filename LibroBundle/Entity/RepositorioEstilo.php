<?php
namespace RGM\eLibreria\LibroBundle\Entity;

use Doctrine\ORM\EntityRepository;

class RepositorioEstilo extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('denominacion' => 'ASC'));
    }
}
?>