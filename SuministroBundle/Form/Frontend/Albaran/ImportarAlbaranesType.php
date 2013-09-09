<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImportarAlbaranesType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
	
		$builder -> add('fichero', 'file', array(
				'required' => true,
				'disabled' => false,
				'label' => 'Seleccione Fichero',
		));
	}
	
	public function getName(){
		return 'rgarcia_entrelineas_suministrobundle_importaralbaranestype';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\ImportarAlbaran',
		));
	}	
}
?>