<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Editorial;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditorialVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombre', 'text', array(
						'required' => false,
						'disabled' => true,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_editorialvisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Editorial',
		));
	}
}
?>