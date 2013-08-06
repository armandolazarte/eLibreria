<?php
namespace RGM\eLibreria\UsuarioBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RGM\eLibreria\UsuarioBundle\Entity\Usuario;
use RGM\eLibreria\EmpresaBundle\Entity\Empleado;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Usuarios extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
	private $container;
	
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
	
	public function getOrder(){
		return 20;
	}

	public function load(ObjectManager $manager){
		$repo_Rol = $manager->getRepository('RGMELibreriaUsuarioBundle:Rol');
		$repo_Empleado = $manager -> getRepository('RGMELibreriaEmpresaBundle:Empleado');
		
		$e1 = $repo_Empleado -> findOneBy(array(
				'nombre' => 'Inmaculada',
				'apellidos' => 'Alvarez Bejarano'
		));
		
		$e2 = $repo_Empleado -> findOneBy(array(
				'nombre' => 'Jesus',
				'apellidos' => 'Alvarez Bejarano'
		));
		
		$e3 = $repo_Empleado -> findOneBy(array(
				'nombre' => 'Inmaculada',
				'apellidos' => 'Bejarano Vera'
		));
		
		$e4 = $repo_Empleado -> findOneBy(array(
				'nombre' => 'Jose',
				'apellidos' => 'Alvarez Andujar'
		));
		
		$e5 = $repo_Empleado -> findOneBy(array(
				'nombre' => 'Rafael',
				'apellidos' => 'Garcia Martin'
		));
		
		$rol_Admin = $repo_Rol->findOneBy(array('denominacion' => 'ROLE_ADMIN'));
		
		$encoder = $this->container->get('security.encoder_factory');
		
		$entidad = new Usuario($e1);
		
		$entidad->setUsername('inma');
		$entidad->setSalt(md5(time()));
		$entidad->setPassword('i');
		$entidad->setPassword($encoder->getEncoder($entidad)->
									encodePassword(
											$entidad->getPassword(), 
											$entidad->getSalt()));

		$entidad->addRole($rol_Admin);

		$manager->persist($entidad);
		
		$entidad = new Usuario($e2);
		
		$entidad->setUsername('jesus');
		$entidad->setSalt(md5(time()));
		$entidad->setPassword('j');
		$entidad->setPassword($encoder->getEncoder($entidad)->
									encodePassword(
											$entidad->getPassword(), 
											$entidad->getSalt()));

		$entidad->addRole($rol_Admin);

		$manager->persist($entidad);
		
		$entidad = new Usuario($e3);
		
		$entidad->setUsername('inmaculada');
		$entidad->setSalt(md5(time()));
		$entidad->setPassword('i');
		$entidad->setPassword($encoder->getEncoder($entidad)->
									encodePassword(
											$entidad->getPassword(), 
											$entidad->getSalt()));

		$entidad->addRole($rol_Admin);

		$manager->persist($entidad);
		
		$entidad = new Usuario($e4);
		
		$entidad->setUsername('jose');
		$entidad->setSalt(md5(time()));
		$entidad->setPassword('j');
		$entidad->setPassword($encoder->getEncoder($entidad)->
									encodePassword(
											$entidad->getPassword(), 
											$entidad->getSalt()));

		$entidad->addRole($rol_Admin);

		$manager->persist($entidad);
		
		$entidad = new Usuario($e5);
		
		$entidad->setUsername('rafa');
		$entidad->setSalt(md5(time()));
		$entidad->setPassword('r');
		$entidad->setPassword($encoder->getEncoder($entidad)->
									encodePassword(
											$entidad->getPassword(), 
											$entidad->getSalt()));

		$entidad->addRole($rol_Admin);

		$manager->persist($entidad);
		
		$manager -> flush();
	}
}
?>
