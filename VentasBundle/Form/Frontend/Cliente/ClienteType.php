<?php 
namespace RGM\eLibreria\VentasBundle\Form\Frontend\Cliente;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombreContacto', 'text', array(
			'required' => true,
			'disabled' => false,
			'label' => 'Nombre de contacto'
		))
		-> add('telefono', 'text', array(
			'required' => false,
			'disabled' => false,
			'label' => 'Telefono de contacto'
		))
		-> add('movil', 'text', array(
			'required' => false,
			'disabled' => false,
			'label' => 'Movil'
		))
		-> add('direccion', 'text', array(
			'required' => false,
			'disabled' => false,
			'label' => 'Dirección'
		));
	}

	public function getName(){
		return 'rgarcia_entrelineas_ventasbundle_editorialtype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\VentasBundle\Entity\Cliente',
		));
	}
}
?>