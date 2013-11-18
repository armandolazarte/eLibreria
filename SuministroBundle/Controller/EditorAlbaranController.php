<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use RGM\eLibreria\IndexBundle\Controller\GridController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use RGM\eLibreria\LibroBundle\Entity\Ejemplar;
use RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran;
use RGM\eLibreria\LibroBundle\Entity\Libro;
use RGM\eLibreria\LibroBundle\Entity\Editorial;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use Symfony\Component\HttpFoundation\Request;

class EditorAlbaranController extends Asistente{
	private $bundle = 'suministrobundle';
	private $controller = 'albaran';	
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	public function editarAlbaranAction(Request $peticion, $id = null){
		$opciones = $this->getArrayOpcionesVista();
		$em = $this->getEm();
		
		if($id){
			$albaran = $em->getRepository($this->getEntidadLogico($this->getParametro('entidad')))->find($id);
			
			if($albaran){
				$opciones['albaran'] = $albaran;
			}			
		}
		
		$info_contratos = $this->getParametro('contrato');
		$opciones['contratos'] = $em->getRepository($info_contratos['repositorio'])->findAll();
		
		$opciones['ajax'] = $this->getParametro('ajax');
		
		return $this->render($this->getPlantilla('crearAlbaran'), $opciones);
	}
}
?>