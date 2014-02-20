<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Autor;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CrearAutorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$siguiente = false;
		if(array_key_exists('siguiente', $options)){
			$siguiente = true;
		}
		
		$builder -> add('siguiente', 'checkbox', array(
						'required' => false,
						'disabled' => false,
						'mapped' => false,
						'data' => $siguiente,
						'label' => 'Seleccione para seguir creando nuevos autores'
				))
				
				-> add('nombre', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				
				-> add('apellidos', 'text', array(
						'required' => true,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_crearautortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Autor',
		));
		
		$resolver->setOptional(array('siguiente'));
	}
}
?>