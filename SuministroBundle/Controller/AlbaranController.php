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

class AlbaranController extends Asistente{
	private $bundle = 'suministrobundle';
	private $controller = 'albaran';	

	private $seccion = 'Gestor de Suministros';
	private $subseccion = 'Albaranes';
	
	private $numElementosPagina = 33;
	
	private $repositorio_libro = 'RGMELibreriaLibroBundle:Libro';
	private $entidad_libro = 'RGM\eLibreria\LibroBundle\Entity\Libro';
	
	private $entidad_ejemplar = 'RGM\eLibreria\LibroBundle\Entity\Ejemplar';
	
	private $entidad_recargo = 'RGMELibreriaSuministroBundle:Recargo';
	
	private $repositorio_editorial = 'RGMELibreriaLibroBundle:Editorial';
	private $entidad_editorial = 'RGM\eLibreria\LibroBundle\Entity\Editorial';
	
	private $logicoBundle = 'RGMELibreriaSuministroBundle';
	private $ruta_inicio = 'rgm_e_libreria_suministro_albaran_homepage';
	
	private $entidad = 'Albaran';
	private $entidad_clase = 'RGM\eLibreria\SuministroBundle\Entity\Albaran';
	private $entidad_item_albaran = 'RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran';
	private $alias = 'a';
	
	private $entidad_importar_albaran = 'RGM\eLibreria\SuministroBundle\Entity\ImportarAlbaran';
	
	private $nombreFormularios = array(
			'creadorAlbaran' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\CrearAlbaranType',
			'editor' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\AlbaranType',
			'importar' => 'RGM\eLibreria\SuministroBundle\Form\Frontend\Albaran\ImportarAlbaranesType'
	);
	
	private $plantilla_ver_albaran = 'RGMELibreriaSuministroBundle:Albaran:verAlbaran.html.twig';
	private $plantilla_crear_albaran = 'RGMELibreriaSuministroBundle:Albaran:crearAlbaran.html.twig';
	private $plantilla_importar_albaran = 'RGMELibreriaSuministroBundle:Albaran:importarAlbaran.html.twig';
	private $flash_crear = 'Albaran creado con exito';
	
	private $grid_boton_editar = 'Editar';
	private $grid_ruta_editar = 'rgm_e_libreria_suministro_albaran_editar';
	private $titulo_editar = 'Editar albaran';
	private $titulo_submit_editar = 'Actualizar';
	private $flash_editar = 'Albaran editado con exito';
	
	private $grid_boton_borrar = 'Borrar';
	private $grid_ruta_borrar = 'rgm_e_libreria_suministro_albaran_borrar';
	private $titulo_borrar = 'Confirmar Borrado';
	private $msg_borrar = 'Se va a proceder a borrar los siguientes datos.';
	private $titulo_form_borrar = 'Borrar albaran';
	private $msg_confirmar_borrar = '¿Realmente desea borrar el albaran?';
	private $titulo_submit_borrar = '¡Si, Estoy seguro!';
	private $flash_borrar = 'Albaran borrado con exito';
	
	public function __construct(){
		parent::__construct(
				$this->bundle,
				$this->controller);
	}
	
	private function getGrid(){
		$grid = new GridController($this->getEntidadLogico($this->getParametro('entidad')), $this);
		
		$columnaContrato = new BlankColumn(array('id' => 'contrato', 'title' => 'Contrato'));
		$columnaTotal = new BlankColumn(array('id' => 'total', 'title' => 'Total'));
		$columnaVer = new BlankColumn(array('id' => 'ver', 'title' => 'Ver Albaran', 'safe' => false));
		
		$grid->getGrid()->addColumn($columnaContrato);
		$grid->getGrid()->addColumn($columnaTotal);
		$grid->getGrid()->addColumn($columnaVer);
		
		$grid->getSource()->manipulateRow(function($row){
			$entidad = $row->getEntity();
				
			$enlaceVer = '<a onclick=\'window.open(this.href, "mywin","left=20,top=20,width=960,height=500,toolbar=1,resizable=0"); return false;\' href="' . $this->generateUrl('rgm_e_libreria_suministro_albaran_ver', array('id' => $entidad->getId())) . '">Ver Albaran</a>';
				
			$row->setField('contrato', $entidad->getContrato());
			$row->setField('ver', $enlaceVer);
				
			if(!$entidad->getTotal()){
				$this->calcularValorAlbaran($entidad);
			}
			$row->setField('total', round($entidad->getTotal(), 2) . '€');
				
			return $row;
		});
		
		return $grid;
	}
	
	private function getOpcionesGridAjax(){	
		return $this->getArrayOpcionesGridAjax(
				$this->getParametro('grid_boton_editar'),
				$this->getParametro('grid_ruta_editar'),
				null,
				null,
				null);
	}
	
	private function getOpcionesVista(){	
		$opciones = $this->getArrayOpcionesVista($this->getOpcionesGridAjax());
	
		return $opciones;
	}
	
	private function registrarLineaAlbaran($linea){
		
	}
	
	public function verAlbaranesAction(Request $peticion){
		$render = null;
		$grid = $this->getGrid();
		
		if($peticion->isXmlHttpRequest()){
			$grid->setOpciones($this->getOpcionesGridAjax());
			$render = $grid->getRenderAjax();
		}
		else{
			$grid->setOpciones($this->getOpcionesVista());
			$render = $grid->getRender($this->getPlantilla('principal'));
		}
		
		return $render;
	}
	
	public function importarAction(){
		$peticion = $this->getRequest();
		$opciones = $this->getOpcionesPlantilla();
		
		$importar = $this->getNuevaInstancia($this->entidad_importar_albaran);
		
		$opciones['formulario'] = $this->createForm($this->getFormulario('importar'), $importar);
		
		$albaranesSinCrear = array();
		$numEjemplares = 0;
		
		if($peticion->getMethod() == "POST"){
			$opciones['formulario']->bind($peticion);
			
			if($opciones['formulario']->isValid()){
				$fichero = $this->parsearFichero($importar->getFichero());
				$em = $this->getEm();
				
				foreach($fichero as $linea){					
					//Buscar/crear Libro
					$libro = $em->getRepository('RGMELibreriaLibroBundle:Libro')->find($linea['isbn']);
					
					if(!$libro){
						$libro = new Libro();
						
						$libro->setIsbn($linea['isbn']);
						$libro->setTitulo($linea['titulo']);
						
						$editorial = $em->getRepository('RGMELibreriaLibroBundle:Editorial')->findOneBy(array(
								"nombre" => $linea['editorial']
						));
						
						if(!$editorial){
							$editorial = new Editorial();
							
							$editorial->setNombre($linea['editorial']);
							
							$em->persist($editorial);
						}
						
						$libro->setEditorial($editorial);
						
						$em->persist($libro);
						$em->flush();
					}
				}
				
				foreach($fichero as $linea){
					$libro = $em->getRepository('RGMELibreriaLibroBundle:Libro')->find($linea['isbn']);
						
					$albaran = $em->getRepository('RGMELibreriaSuministroBundle:Albaran')->find($linea['albaran']);
					
					if($albaran){
						$numEjemplar = $linea['num1'] + $linea['num2'];
						
						for($i = 0; $i < $numEjemplar ; $i++){
							$ejemplar = new Ejemplar($libro);
							
							$ejemplar->setPrecio((float)preg_replace('/,/', '.', $linea['precio']));
							$ejemplar->setIVA((float)preg_replace('/,/', '.', $linea['iva']));
							
							$em->persist($ejemplar);
							
							$itemAlbaran = new ItemAlbaran();
							
							$itemAlbaran->setAlbaran($albaran);
							$itemAlbaran->setEjemplar($ejemplar);
							$itemAlbaran->setDescuento((float)preg_replace('/,/', '.', $linea['desc']));
							
							$em->persist($itemAlbaran);
							
							$numEjemplares++;
						}
					}
					else{
						$albaranesSinCrear[] = $linea['albaran'];
					}
				}
				
				if(count($albaranesSinCrear) == 0){
					$em->flush();
				}
			}
		}
		
		$opciones['albaranesSinCrear'] = $albaranesSinCrear;
		$opciones['numEjemplaresCreados'] = $numEjemplares;
		
		$opciones['formulario'] = $opciones['formulario']->createView();
		
		return $this->render($this->plantilla_importar_albaran, $opciones);
	}
	
	private function parsearFichero($fichero){
		$res = array();
		$lectura = array();
		$keys = array();
		
		if(($pF = fopen($fichero, 'r')) != false){
			while(!feof($pF)){
				$lectura[] = fgets($pF);	
			}			
			
			fclose($pF);
		}		
		
		$keys = explode(";", $lectura[0]);
		$claves = array();
		$lineas = array();

		foreach($keys as $k){
			$k = str_replace("\"", "", $k);
			$k = str_replace("\n", "", $k);
			$k = str_replace("\r", "", $k);
			
			$claves[] = $k;
		}
		
		for($i = 1; $i < count($lectura) - 1; $i++){
			$linea = explode(";", $lectura[$i]);
			
			$lineaAux = array();
			$j = 0;
			
			foreach($claves as $c){				
				$linea[$j] = str_replace("\"", "", $linea[$j]);
				$linea[$j] = str_replace("\n", "", $linea[$j]);
				$linea[$j] = str_replace("\r", "", $linea[$j]);
				
				$lineaAux[$c] = $linea[$j];
				
				$j++;
			}
			
			$lineas[] = $lineaAux;
		}
		
		return $lineas;
	}
	
	public function crearAlbaranAction(){
		$peticion = $this->getRequest();
		$opciones = $this->getOpcionesPlantilla();
		$em = $this->getEm();
		
		$opciones['ruta_form'] = $this->generateUrl('rgm_e_libreria_suministro_albaran_crear');
		$opciones['salida'] = null;
		$opciones['path_ajax_autocompletar'] = $this->generateUrl('rgm_e_libreria_suministrobundle_albaran_buscar_referencia_ajax');
						
		$entidad = $this->getNuevaInstancia($this->entidad_clase);
		
		$opciones['form'] = $this->createForm($this->getFormulario('creadorAlbaran'), $entidad);
		
		if($peticion->getMethod() == "POST"){
			$opciones['form']->bind($peticion);
			
			if($opciones['form']->isValid()){
				$lineas = $opciones['form'] -> get('lineas') -> getData();
				
				//Persisto el albaran
				$em->persist($entidad);
				$em->flush();
				
				//Recorro todas las lineas del albaran
				foreach($lineas as $l){
					RegistroLineaAlbaran::registrarLineaAlbaran($l, $entidad, $em);
				}					
				
				$this->setFlash($this->flash_crear);
				return $this->irInicio();
			}
		}
		
		$opciones['form'] = $opciones['form']->createView();
		
		return $this->render($this->plantilla_crear_albaran, $opciones);
	}
	
	public function crearAlbaranNuevoAction(Request $peticion){
		$opciones = $this->getArrayOpcionesVista();
		
		$opciones['ajax'] = $this->getParametro('ajax');
		
		return $this->render($this->getPlantilla('crearAlbaran'), $opciones);
	}
	
	public function editarAlbaranAction($id){
		$peticion = $this->getRequest();
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
		$entidad = $em->getRepository($this->getNombreEntidad())->find($id);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
	
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_editar, array('id' => $id));
		$opciones['titulo_ventana'] = $this -> titulo_editar;
		$opciones['titulo_submit'] = $this -> titulo_submit_editar;
	
		$opciones['form'] = $this -> createForm($this -> getFormulario('editor'), $entidad);
			
		if($peticion -> getMethod() == "POST"){
			$opciones['form'] -> bind($peticion);
	
			if($opciones['form'] -> isValid()){
				$em -> persist($entidad);
				$em -> flush();
					
				$this -> setFlash($this -> flash_editar);
				return $this -> irInicio();
			}
		}
			
		$grid = $this -> getGrid();
		$grid -> setOpciones($opciones);
	
		return $grid -> getRenderVentanaModal();
	}
	
	public function borrarAlbaranAction($id){
		$peticion = $this->getRequest();
		if($peticion->isXmlHttpRequest()){
			return $this->irInicio();
		}
	
		$em = $this->getEm();
		$entidad = $em->getRepository($this->getNombreEntidad())->find($id);
	
		if(!$entidad){
			return $this->irInicio();
		}
	
		$opciones = $this->getOpcionesVista();
	
		$opciones['path_form'] = $this -> generateUrl($this -> grid_ruta_borrar, array('id' => $id));
		$opciones['titulo_ventana'] = $this -> titulo_borrar;
		$opciones['msg'] = $this -> msg_borrar;
		$opciones['titulo_form'] = $this -> titulo_form_borrar;
		$opciones['msg_confirmar'] = $this -> msg_confirmar_borrar;
		$opciones['titulo_submit'] = $this -> titulo_submit_borrar;
	
		$opciones['form'] = $this->createForm($this->getFormulario('visor'), $entidad);
	
		if($peticion->getMethod() == "POST"){
			$opciones['form']->bind($peticion);
				
			if($opciones['form']->isValid()){
				$em->remove($entidad);
				$em->flush();
	
				$this->setFlash($this->flash_borrar);
				return $this->irInicio();
			}
		}
	
		$grid = $this->getGrid();
		$grid->setOpciones($opciones);
	
		return $grid->getRenderVentanaModal();
	}
	
	public function verAlbaranAction($id){
		$em = $this->getEm();
		
		$albaran = $em->getRepository($this->getNombreEntidad())->find($id);
		$opciones = array();
		
		$opciones['error'] = null;
		
		if(!$albaran){
			$opciones['error'] = 'Albaran no encontrado';
		}
		else{
			$calculoAlbaran = $this->calcularValorAlbaran($albaran);
			
			$opciones['albaran'] = $albaran;
			
			$opciones['bases'] = $calculoAlbaran['bases'];
				
			$opciones['bImp'] = $calculoAlbaran['bImp'];
			$opciones['bIva'] = $calculoAlbaran['bIva'];
			$opciones['bRec'] = $calculoAlbaran['bRec'];
			$opciones['bTotal'] = $calculoAlbaran['bTotal'];
				
			$opciones['lineas'] = $calculoAlbaran['lineas'];
			$opciones['numPaginas'] = $calculoAlbaran['numPaginas'];
		}
		
		return $this->render($this->plantilla_ver_albaran, $opciones);
	}
	
	private function calcularValorAlbaran($albaran){
		$salida = array();
		$em = $this->getEm();
			
		$recargos = $em->getRepository($this->entidad_recargo)->findAll();
			
		$bases = new ArrayCollection();
			
		foreach($recargos as $recargo){
			$i = array();
		
			$i['rec'] = $recargo->getRecargo();
			$i['iva'] = $recargo->getIva();
		
			$i['baseImp'] = 0;
			$i['ivaImp'] = 0;
			$i['recImp'] = 0;
		
			$bases->set((string) $recargo->getIva(), $i);
		}
			
		$lineasAlbaran = new ArrayCollection();
		$items = $albaran->getItems();
			
		foreach($items as $item){
			$elemento = $item->getElemento();
			$referencia = $elemento->getReferencia();
		
			if($lineasAlbaran->containsKey($referencia)){
				$linea = $lineasAlbaran->get($referencia);
				$linea['numEjemplares'] += 1;
				$linea['vendidos'] += $elemento->getVendido();
					
				$lineasAlbaran->set($referencia, $linea);
			}
			else{
				$linea['ref'] = $referencia;
				$linea['titulo'] = $elemento->getTitulo();
				$linea['iva'] = $elemento->getIVA();
				$linea['precio'] = $elemento->getPrecio();
				$linea['numEjemplares'] = 1;
				$linea['vendidos'] = 0 + $elemento->getVendido();
				$linea['desc'] = $item->getDescuento();
					
				$lineasAlbaran->set($referencia, $linea);
			}
		
			$linea = $lineasAlbaran->get($referencia);
		
			$iva = $linea['iva'];
			$base = $bases->get((string) $iva);
			
			$baseImponible = $linea['precio'] * (1 - $linea['desc']);
		
			$base['baseImp'] += $baseImponible;
			$base['ivaImp'] += $baseImponible * $iva;
			$base['recImp'] += $baseImponible * $base['rec'];
		
			$bases->set((string) $iva, $base);
		}
			
		$bImp = 0;
		$bIva = 0;
		$bRec = 0;
		$bTotal = 0;
			
		foreach($bases as $base){
			$bImp += $base['baseImp'];
			$bIva += $base['ivaImp'];
			$bRec += $base['recImp'];
		}
			
		$bTotal = $bImp + $bIva + $bRec;

		$salida['bases'] = $bases;
		
		$salida['bImp'] = $bImp;
		$salida['bIva'] = $bIva;
		$salida['bRec'] = $bRec;
		
		$albaran->setTotal($bTotal);
		$salida['bTotal'] = $bTotal;
		
		$em->persist($albaran);
		$em->flush();
		
		$salida['lineas'] = $lineasAlbaran->getValues();
		
		$salida['numPaginas'] = ceil(count($lineasAlbaran) / $this->numElementosPagina);
				
		return $salida;
	}
}
?>