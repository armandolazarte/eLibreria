<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemAlbaranType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('isLibro', 'checkbox', array(
						'required' => false,
						'disabled' => false,
						'attr' => array('class' => 'isLibro'),
				))
				->add('ejemplar', 'collection', array(
								'type' => new EjemplarType(),
								'required' => false,
								'allow_add' => true,
								'allow_delete' => true,
								'label' => false,
				))
				->add('articulo', 'collection', array(
								'type' => new ArticuloType(),
								'required' => false,
								'allow_add' => true,
								'allow_delete' => true,
								'label' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_suministrobundle_itemalbarantype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran',
		));
	}
}
?>