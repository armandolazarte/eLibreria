<?php 
namespace RGM\eLibreria\VentasBundle\Form\Frontend\Venta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VentaVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('fecha', 'date', array(
						'required' => false,
						'disabled' => true,						
						'widget' => 'single_text',
						'format' => 'dd/MM/yyyy',
				))
				-> add('total', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('metodoPago', 'choice', array(
						'required' => false,
						'disabled' => true,
						'choices' => array(
								'1' => 'Efectivo',
								'2' => 'Tarjeta'
						),
						'label' => 'Metodo de pago'
				))
				-> add('observaciones', 'textarea', array(
						'required' => false,
						'disabled' => true,
				));
	}

	public function getName(){
		return 'rgm_elibreria_ventabundle_ventavisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\VentasBundle\Entity\Venta',
		));
	}
}
?>