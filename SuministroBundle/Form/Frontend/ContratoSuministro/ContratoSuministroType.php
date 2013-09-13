<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\ContratoSuministro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContratoSuministroType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('distribuidora', 'entity', array(
						'required' => true,
						'disabled' => false,
						'class' => 'RGMELibreriaSuministroBundle:Distribuidora',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija una distribuidora"							
				))
				-> add('tipoPago', 'choice', array(
						'required' => false,
						'disabled' => false,
						'choices' => array(
    							'0' => 'Deposito', 
    							'1' => 'En Efectivo'),
						'attr' => array('class' => 'tipoPago'),
						'label' => 'Tipo de pago',
						'empty_value' => false
				))
				-> add('precioTotal', 'text', array(
						'required' => false,
						'disabled' => false,
				))
				-> add('fechaAlta', 'datetime', array(
						'required' => false,
						'disabled' => false,
						'label' => 'Fecha de Alta',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				))
				-> add('fechaBaja', 'datetime', array(
						'required' => false,
						'disabled' => false,
						'label' => 'Fecha de Baja',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				))
				-> add('observaciones', 'textarea', array(
						'required' => false,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_contratosuministrotype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\ContratoSuministro',
		));
	}
}
?>