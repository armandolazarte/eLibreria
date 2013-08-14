<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\Banco;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BancoType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('nombre', 'text', array(
						'required' => true,
						'disabled' => false
					))
					-> add('cuentaBancaria', 'text', array(
						'required' => false,
						'disabled' => false							
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_bancotype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\Banco',
		));
		
	}
}
?>