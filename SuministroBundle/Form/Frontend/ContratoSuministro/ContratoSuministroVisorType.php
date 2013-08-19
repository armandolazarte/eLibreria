<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\ContratoSuministro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratoSuministroVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombre', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('direccion', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('telefonoContacto', 'text', array(
						'required' => false,
						'disabled' => true,
				))
						-> add('aval', 'entity', array(
								'required' => false,
								'disabled' => true,
								'class' => 'RGMELibreriaFinanciacionBundle:Aval',
								'multiple' => false,
								'expanded' => false,
                				'empty_value' => "Elija una aval (si es necesario)"							
						));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_contratosuministrovisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\ContratoSuministro',
		));
	}
}
?>