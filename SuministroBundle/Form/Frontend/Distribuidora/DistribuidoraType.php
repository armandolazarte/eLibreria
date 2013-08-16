<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Distribuidora;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DistribuidoraType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombre', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('direccion', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('telefonoContacto', 'text', array(
						'required' => true,
						'disabled' => false,
				))
						-> add('aval', 'entity', array(
								'required' => false,
								'class' => 'RGMELibreriaFinanciacionBundle:Aval',
								'multiple' => false,
								'expanded' => false,
                				'empty_value' => "Elija una aval (si es necesario)"							
						));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_distribuidoratype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Distribuidora',
		));
	}
}
?>