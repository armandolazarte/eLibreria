<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Estilo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstiloType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('denominacion', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('padre', 'entity', array(
						'required' => false,
						'disabled' => false,
						'class' => 'RGMELibreriaLibroBundle:Estilo',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Sin padre"							
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_estilotype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Estilo',
		));
	}
}
?>