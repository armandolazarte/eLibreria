<?php
namespace RGM\eLibreria\UsuarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RGM\eLibreria\UsuarioBundle\Entity\Rol;

class Roles extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 10;
	}

	public function load(ObjectManager $manager){
		$roles = array(
				array('tipo' => 'ROLE_USUARIO'),
				array('tipo' => 'ROLE_ADMIN')
			);

		foreach ($roles as $rol){
			$entidad = new Rol();

			$entidad->setDenominacion($rol['tipo']);

			$manager->persist($entidad);
		}

		$manager->flush();
	}
}
?>
