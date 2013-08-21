<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Recargo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecargoType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('iva', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('recargo', 'text', array(
						'required' => true,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_recargotype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Recargo',
		));
	}
}
?>