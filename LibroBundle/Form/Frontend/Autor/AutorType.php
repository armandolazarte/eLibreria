<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Autor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombre', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				
				-> add('apellidos', 'text', array(
						'required' => true,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_autortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Autor',
		));
	}
}
?>