<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Libro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LibroLineaType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('isbn', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('titulo', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('editorial', 'entity', array(
						'required' => false,
						'class' => 'RGMELibreriaLibroBundle:Editorial',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija una editorial"							
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_librolineatype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Libro',
		));
	}
}
?>