<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Estilo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstiloVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('denominacion', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('padre', 'entity', array(
						'required' => false,
						'disabled' => true,
						'class' => 'RGMELibreriaLibroBundle:Estilo',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Sin padre"							
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_estilovisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Estilo',
		));
	}
}
?>