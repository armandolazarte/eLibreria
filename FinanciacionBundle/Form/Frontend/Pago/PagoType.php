<?php 
namespace RGM\eLibreria\FinanciacionBundle\Form\Frontend\Pago;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PagoType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('gasto', 'entity', array(
						'required' => false,
						'disabled' => true,
						'multiple' => false,
						'expanded' => false,
						'class' => 'RGMELibreriaFinanciacionBundle:GastoCorriente',
						'label' => 'Pago del gasto'
					))
					-> add('fecha', 'datetime', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Fecha de pago',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
					))
					-> add('valor', 'text', array(
						'required' => true,
						'disabled' => false,
					));
	}
	
	public function getName(){
		return 'rgm_elibreria_financiacionbundle_pagotype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\FinanciacionBundle\Entity\Pago',
		));
		
	}
}
?>