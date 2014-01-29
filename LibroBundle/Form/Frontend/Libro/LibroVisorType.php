<?php 
namespace RGM\eLibreria\LibroBundle\Form\Frontend\Libro;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LibroVisorType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		
		$builder -> add('isbn', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				
				-> add('titulo', 'text', array(
						'required' => false,
						'disabled' => true,
				))
				
						-> add('editorial', 'entity', array(
								'required' => false,
								'disabled' => true,
								'class' => 'RGMELibreriaLibroBundle:Editorial',
								'multiple' => false,
								'expanded' => false,
                				'empty_value' => "Elija una editorial"							
						))
						-> add('autores', 'entity', array(
								'required' => false,
								'disabled' => true,
								'class' => 'RGMELibreriaLibroBundle:Autor',
								'attr' => array('class' => 'cajaEleccionEntidades'),
								'multiple' => true,
								'expanded' => true,								
						))	
						-> add('estilos', 'entity', array(
								'required' => false,
								'disabled' => true,
								'class' => 'RGMELibreriaLibroBundle:Estilo',
								'attr' => array('class' => 'cajaEleccionEntidades'),
								'multiple' => true,
								'expanded' => true,								
						))	
				
				-> add('numPaginas', 'text', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Numero de Paginas'
				))
				-> add('precioOrientativo', 'text', array(
						'required' => false,
						'disabled' => true,
						'label' => 'Precio orientativo'
				))
				
				-> add('sinopsis', 'textarea', array(
						'required' => false,
						'disabled' => true,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_librobundle_librovisortype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\LibroBundle\Entity\Libro',
		));
	}
}
?>