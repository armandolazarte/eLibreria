<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineaAlbaranType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('ref', 'text', array(
							'attr' => array('class' => 'refAjax')
					))
					-> add('editorial', 'text')
					-> add('titulo', 'text')
					-> add('numeroUnidades', 'text')
					-> add('precio', 'text')
					-> add('iva', 'text')
					-> add('descuento', 'text');
	}

	public function getName(){
		return 'rgarcia_entrelineas_suministrobundle_lineaalbarantype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\SuministroBundle\Entity\LineaAlbaran',
		));
	}
}
?>