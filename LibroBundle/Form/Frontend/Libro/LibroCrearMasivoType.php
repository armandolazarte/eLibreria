<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Libro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LibroCrearMasivoType extends AbstractType{
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
						'attr' => array('class' => 'libro_isbn'),
				))
				
				-> add('titulo', 'text', array(
						'required' => true,
						'disabled' => false,
						'attr' => array('class' => 'libro_titulo'),
				))
				
						-> add('editorial', 'entity', array(
								'required' => false,
								'class' => 'RGMELibreriaLibroBundle:Editorial',
								'multiple' => false,
								'expanded' => false,
                				'empty_value' => "Elija una editorial"							
						))
						-> add('autores', 'entity', array(
								'required' => false,
								'class' => 'RGMELibreriaLibroBundle:Autor',
								'attr' => array('class' => 'cajaEleccionEntidades'),
								'multiple' => true,
								'expanded' => true,								
						))	
						-> add('estilos', 'entity', array(
								'required' => false,
								'class' => 'RGMELibreriaLibroBundle:Estilo',
								'attr' => array('class' => 'cajaEleccionEntidades'),
								'multiple' => true,
								'expanded' => true,								
						))	
				
				-> add('numPaginas', 'text', array(
						'required' => false,
						'disabled' => false,
						'label' => 'Numero de Paginas'
				))
				-> add('precioOrientativo', 'text', array(
						'required' => false,
						'disabled' => false,
						'label' => 'Precio orientativo'
				))
				-> add('sinopsis', 'textarea', array(
						'required' => false,
						'disabled' => false,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_librocrearmasivotype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Libro',
		));
		
		$resolver -> setRequired(array('crearMasivo'));
	}
}
?>