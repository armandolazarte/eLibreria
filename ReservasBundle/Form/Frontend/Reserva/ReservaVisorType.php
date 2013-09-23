<?php 
namespace RGM\eLibreria\ReservasBundle\Form\Frontend\Reserva;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservaVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('nombreContacto', 'text', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Nombre de Contacto'
				))
				-> add('telefonoContacto', 'text', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Telefono de Contacto'
				))
				-> add('isbn', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('titulo', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('editorial', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('autor', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				-> add('distribuidora', 'entity', array(
						'required' => false,
						'disabled' => true,
						'class' => 'RGMELibreriaSuministroBundle:Distribuidora',
						'multiple' => false,
						'expanded' => false,
                		'empty_value' => "Elija una distribuidora"							
				))
				-> add('observaciones', 'textarea', array(
						'required' => false,
						'disabled' => true,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_reservasbundle_reservavisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\ReservasBundle\Entity\Reserva',
		));
	}
}
?>