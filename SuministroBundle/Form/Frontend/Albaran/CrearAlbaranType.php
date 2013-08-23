<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CrearAlbaranType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('id', 'text', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Numero de Albaran'							
				))
				-> add('contrato', 'entity', array(
						'required' => true,
						'disabled' => false,
						'class' => 'RGMELibreriaSuministroBundle:ContratoSuministro',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija un contrato"							
				))
				-> add('fechaRealizacion', 'datetime', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Fecha de Realizacion',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				))
				-> add('fechaVencimiento', 'datetime', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Fecha de Vencimiento',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				))
				-> add('lineas', 'collection', array(
						'type' => new LineaAlbaranType(),
						'required' => true,
						'disabled' => false,
						'allow_add' => true,
						'allow_delete' => true,
						'label' => false,
						'mapped' => false
				));
	}
	
	public function getName(){
		return "rgm_e_libreria_suministrobundle_crearalbarantype";
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Albaran',
		));
	}
}
?>