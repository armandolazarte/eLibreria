<?php
namespace RGM\eLibreria\EmpresaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RGM\eLibreria\EmpresaBundle\Entity\Empleado;

class Empleados extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 10;
	}

	public function load(ObjectManager $manager){		
		$empleados = array(
				array(
						"nombre" => "Inmaculada",
						"apellidos" => "Alvarez Bejarano"),
				array(
						"nombre" => "Jesus",
						"apellidos" => "Alvarez Bejarano"),
				array(
						"nombre" => "Inmaculada",
						"apellidos" => "Bejarano Vera"),
				array(
						"nombre" => "Jose",
						"apellidos" => "Alvarez Andujar"),
				array(
						"nombre" => "Rafael",
						"apellidos" => "Garcia Martin")
		);

		foreach($empleados as $e){
			$entidad = new Empleado();
			
			$entidad -> setNombre($e['nombre']);
			$entidad -> setApellidos($e['apellidos']);
			
			$manager -> persist($entidad);
		}

		$manager->flush();
	}
}
?>
