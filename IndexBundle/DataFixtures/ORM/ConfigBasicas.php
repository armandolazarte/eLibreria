<?php

namespace RGM\eLibreria\IndexBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RGM\eLibreria\IndexBundle\Entity\ConfigBasica;

class ConfiguracionesBasicas extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 10;
	}

	public function load(ObjectManager $manager){		
		$opciones = array(
				'titulo' => 'Libreria Virtual',
				'limite_paginado' => 10
		);
		
		foreach ($opciones as $tipo => $valor){
			$entidad = new ConfigBasica();
			
			$entidad -> setTipo($tipo);
			$entidad -> setValor($valor);
			
			$manager -> persist($entidad);
		}

		$manager->flush();
	}
}
?>
