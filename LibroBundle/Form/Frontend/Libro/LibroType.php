<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Libro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LibroType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$opcionesCrearMasivo['required'] = false;
		$opcionesCrearMasivo['disabled'] = false;
		$opcionesCrearMasivo['mapped'] = false;
		$opcionesCrearMasivo['label'] = 'Crear otro libro al finalizar';
		
		if($options['crearMasivo'] == 1){
			$opcionesCrearMasivo['attr'] = array('checked' => 'checked');
		}
		
		$builder -> add('crearMasivo', 'checkbox', $opcionesCrearMasivo)
				-> add('isbn', 'text', array(
						'required' => true,
						'disabled' => false,
				))
				-> add('titulo', 'text', array(
						'required' => true,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_librotype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Libro',
		));
		
		$resolver -> setRequired(array('crearMasivo'));
	}
}
?>