<?php 
namespace RGM\eLibreria\VentasBundle\Form\Frontend\InformeVenta;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InformeType extends AbstractType{
	public function buildForm(FormBuilderInterface $builder, array $options){
		$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		$annos = array();
		
		$annoActual = (int)(new \DateTime('now'))->format('Y');
		
		for($i = 0; $i < 10; $i++){
			$ano = $annoActual - $i;
			$annos[$ano] = $ano;
		}
		
		$builder -> add('mes', 'choice', array(
						'required' => true,
						'disabled' => false,
						'choices' => $meses,
				))				
				-> add('anno', 'choice', array(
						'required' => true,
						'disabled' => false,
						'label' => 'Año',
						'choices' => $annos,
				));
	}

	public function getName(){
		return 'rgarcia_entrelineas_ventasbundle_informetype';
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class' => 'RGM\eLibreria\VentasBundle\Entity\SeleccionInforme',
		));
	}
}
?>