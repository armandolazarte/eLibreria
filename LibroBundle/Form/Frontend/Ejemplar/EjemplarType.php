<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Ejemplar;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EjemplarType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('referencia', 'text');
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_ejemplartype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Ejemplar',
		));
	}
}
?>