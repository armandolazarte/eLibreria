<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\Banco;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BancoVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('nombre', 'text', array(
						'required' => false,
						'disabled' => true
					))
					-> add('cuentaBancaria', 'text', array(
						'required' => false,
						'disabled' => true							
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_bancovisortype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\Banco',
		));
		
	}
}
?>