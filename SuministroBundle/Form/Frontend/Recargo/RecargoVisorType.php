<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Recargo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecargoVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('iva', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('recargo', 'text', array(
						'required' => false,
						'disabled' => true,
				));
	}

	public function getName(){
		return 'rgm_elibreria_suministro_recargovisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\Recargo',
		));
	}
}
?>