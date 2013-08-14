<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\Aval;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AvalType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('banco', 'entity', array(
							'required' => true,
							'disabled' => false,
							'multiple' => false,
							'expanded' => false,
							'class' => 'RGMELibreriaFinanciacionBundle:Banco',
							'empty_value' => 'Elija un banco'
					))
					-> add('cantidad', 'text', array(
							'required' => true,
							'disabled' => false,
							'label' => 'Cuantia del Aval',								
					))
					-> add('fechaAlta', 'datetime', array(
							'required' => true,
							'disabled' => false,
							'label' => 'Fecha de Realizacion',						
							'widget' => 'single_text',
							'format' => 'dd/MM/yyyy',
							'attr' => array('class' => 'dateJquery'),					
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_avaltype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\Aval',
		));
		
	}
}
?>