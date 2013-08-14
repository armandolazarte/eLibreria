<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\Aval;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AvalVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('banco', 'entity', array(
							'required' => false,
							'disabled' => true,
							'multiple' => false,
							'expanded' => false,
							'class' => 'RGMELibreriaFinanciacionBundle:Banco',
							'empty_value' => 'Elija un banco'
					))
					-> add('cantidad', 'text', array(
							'required' => false,
							'disabled' => true,
							'label' => 'Cuantia del Aval',								
					))
					-> add('fechaAlta', 'datetime', array(
							'required' => false,
							'disabled' => true,
							'label' => 'Fecha de Realizacion',						
							'widget' => 'single_text',
							'format' => 'dd/MM/yyyy',
							'attr' => array('class' => 'dateJquery'),					
					))
					-> add('fechaDevolucion', 'text', array(
							'required' => false,
							'disabled' => true,
							'label' => 'Fecha de Devolucion'					
					))
					;
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_avalvisortype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\Aval',
		));
		
	}
}
?>