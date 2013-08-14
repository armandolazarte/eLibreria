<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\GastoCorriente;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GastoCorrienteVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('denominacion', 'text', array(
						'required' => false,
						'disabled' => true
					))
					-> add('empresa', 'text', array(
						'required' => false,
						'disabled' => true
					))
					-> add('banco', 'entity', array(
							'required' => false,
							'disabled' => true,
							'multiple' => false,
							'expanded' => false,
							'class' => 'RGMELibreriaFinanciacionBundle:Banco',
							'empty_value' => 'Elija un banco'
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_gastocorrientevisortype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\GastoCorriente',
		));
		
	}
}
?>