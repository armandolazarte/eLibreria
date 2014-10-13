<?php 
namespace RGM\eLibreria\VentasBundle\Controller;

use RGM\eLibreria\IndexBundle\Controller\Asistente;
use Symfony\Component\HttpFoundation\Request;
use RGM\eLibreria\VentasBundle\Entity\SeleccionInforme;

class InformeVentaController extends Asistente{
	private $bundle = 'ventasbundle';
	private $controlador = 'informeventa';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controlador);
	}
	
	public function generarInformeAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista(array());
		$seleccionInforme = new SeleccionInforme();
		$em = $this->getEm();
		
		$formulario = $this->createForm($this->getFormulario('seleccionInforme'), $seleccionInforme);
		
		if($peticion->getMethod() == "POST"){
			$formulario->bind($peticion);
			
			$repositorio = $em->getRepository('RGMELibreriaVentasBundle:Venta');
			$ventas = $repositorio->findVentasPorDistribuidorasFiltroFecha($seleccionInforme->getMes(), $seleccionInforme->getAnno());
			
			$opciones['resultadoBusqueda'] = $seleccionInforme->getMesString() . '/' . $seleccionInforme->getAnno();
		}
		
		$opcionesFormulario['form'] = $formulario->createView();
		$opcionesFormulario['ruta_destino_form'] = $this->generateUrl($this->getInicio());
		$opcionesFormulario['titulo_submit'] = 'Generar Informe';
		
		$opciones['formulario'] = $opcionesFormulario;
		
		return $this->render($this->getPlantilla('principal'), $opciones);
	}}
?>