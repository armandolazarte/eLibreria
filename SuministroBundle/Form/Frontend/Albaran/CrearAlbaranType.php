<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CrearAlbaranType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$builder -> add('lineas', 'collection', array(
						'type' => new LineaAlbaranType(),
						'required' => false,
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