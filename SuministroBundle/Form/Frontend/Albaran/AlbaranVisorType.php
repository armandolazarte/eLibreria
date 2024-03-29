<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlbaranVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('numeroAlbaran', 'text', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Numero de Albaran'							
				))
				-> add('contrato', 'entity', array(
						'required' => false,
						'disabled' => true,
						'class' => 'RGMELibreriaSuministroBundle:ContratoSuministro',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija un contrato"							
				))
				-> add('fechaRealizacion', 'datetime', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Fecha de Realizacion',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				))
				-> add('fechaVencimiento', 'datetime', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Fecha de Vencimiento',						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
						'attr' => array('class' => 'dateJquery'),					
				));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_albaranvisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Albaran',
		));
	}
}
?>