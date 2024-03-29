<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\GastoCorriente;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GastoCorrienteType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('denominacion', 'text', array(
						'required' => true,
						'disabled' => false
					))
					-> add('empresa', 'text', array(
						'required' => true,
						'disabled' => false
					))
					-> add('estable', 'choice', array(
							'required' => true,
							'disabled' => false,
							'choices' => array(
    								'0' => 'No', 
    								'1' => 'Si'),
							'attr' => array('class' => 'estable'),
							'label' => 'Pago estable?',
							'empty_value' => false
					))
					-> add('mensualidad', 'text', array(
						'required' => false,
						'disabled' => false,
						'attr' => array('class' => 'mensualidad'),
					))
					-> add('banco', 'entity', array(
							'required' => true,
							'disabled' => false,
							'multiple' => false,
							'expanded' => false,
							'class' => 'RGMELibreriaFinanciacionBundle:Banco',
							'empty_value' => 'Elija un banco'
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_gastocorrientetype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\GastoCorriente',
		));
		
	}
}
?>