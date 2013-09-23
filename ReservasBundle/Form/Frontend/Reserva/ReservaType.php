<?php 
namespace RGM\eLibreria\ReservasBundle\Form\Frontend\Reserva;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservaType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombreContacto', 'text', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Nombre de Contacto'
				))
				-> add('telefonoContacto', 'text', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Telefono de Contacto'
				))
				-> add('isbn', 'text', array(
						'required' => false,
						'disabled' => false,
				))
				-> add('titulo', 'text', array(
						'required' => false,
						'disabled' => false,
				))
				-> add('editorial', 'text', array(
						'required' => false,
						'disabled' => false,
				))
				-> add('autor', 'text', array(
						'required' => false,
						'disabled' => false,
				))
				-> add('distribuidora', 'entity', array(
						'required' => false,
						'disabled' => false,
						'class' => 'RGMELibreriaSuministroBundle:Distribuidora',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija una distribuidora"							
				))
				-> add('observaciones', 'textarea', array(
						'required' => false,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_reservasbundle_reservatype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\ReservasBundle\Entity\Reserva',
		));
	}
}
?>