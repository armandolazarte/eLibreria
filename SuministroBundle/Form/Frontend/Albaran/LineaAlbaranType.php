<?php 
namespace RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineaAlbaranType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('ref', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'refAjax', 'value' => 'ISBN')
					))
					-> add('titulo', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'titulo', 'value' => 'Titulo')
					))
					-> add('editorial', 'text', array(
							'required' => false,
							'disabled' => false,
							'attr' => array('class' => 'editorial', 'value' => 'Editorial')
					))
					-> add('numeroUnidades', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'numeroUnidades', 'value' => 'Unid.')
					))
					-> add('precio', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'precio', 'value' => 'Precio')
					))
					-> add('iva', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'iva', 'value' => 'IVA')
					))
					-> add('descuento', 'text', array(
							'required' => true,
							'disabled' => false,
							'attr' => array('class' => 'descuento', 'value' => 'Desc')
					));
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