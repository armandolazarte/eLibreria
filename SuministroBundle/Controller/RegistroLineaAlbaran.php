<?php 
namespace RGM\eLibreria\SuministroBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use RGM\eLibreria\LibroBundle\Entity\Libro;
use RGM\eLibreria\LibroBundle\Entity\Editorial;
use RGM\eLibreria\SuministroBundle\Entity\LineaAlbaran;
use RGM\eLibreria\SuministroBundle\Entity\Albaran;
use RGM\eLibreria\LibroBundle\Entity\Ejemplar;
use RGM\eLibreria\SuministroBundle\Entity\Articulo;
use RGM\eLibreria\SuministroBundle\Entity\ItemAlbaran;

abstract class RegistroLineaAlbaran{
	private static $entidad_libro = 'RGMELibreriaLibroBundle:Libro';
	private static $entidad_editorial = 'RGMELibreriaLibroBundle:Editorial';
	
	public static function registrarLineaAlbaran(LineaAlbaran $linea, Albaran $albaran, EntityManager $em){
		$ref = $linea->getRef();
		
		$titulo = $linea->getTitulo();
		$numUnidades = $linea->getNumeroUnidades();
		$precio = $linea->getPrecio();
		$iva = $linea->getIva();
		$desc = $linea->getDescuento();
		
		$articulos = new ArrayCollection();
		
		if(preg_match('/^978/', $ref)){
			//Es un libro
			$isbn = $ref;
			$editorialNombre = $linea->getEditorial();
			
			$libro = $em->getRepository(self::$entidad_libro)->find($isbn);
				
			if(!$libro){
				$libro = new Libro();
			
				$libro->setIsbn($isbn);
				$libro->setTitulo($titulo);
			
				$editorial = $em->getRepository(self::$entidad_editorial)->findOneBy(array(
						"nombre" => $editorialNombre
				));
			
				if(!$editorial){
					$editorial = new Editorial();
						
					$editorial->setNombre($editorialNombre);
						
					$em->persist($editorial);
				}
			
				$libro->setEditorial($editorial);
			
				$em->persist($libro);
				$em->flush();
			}
			
			//Crear Ejemplar
			for($unidad = 0; $unidad < $numUnidades; $unidad++){
				$ejemplar = new Ejemplar($libro);
			
				$ejemplar->setPrecio($precio);
				$ejemplar->setIva($iva);
			
				$em->persist($ejemplar);
				
				$articulos->add($ejemplar);
			}
		}
		else{
			//Es un articulo
				
			//Crear Articulo
			for($unidad = 0; $unidad < $numUnidades; $unidad++){
				$articulo = new Articulo();
			
				$articulo->setRef($ref);
				$articulo->setTitulo($titulo);
				$articulo->setPrecio($precio);
				$articulo->setIva($iva);
			
				$em->persist($articulo);
				
				$articulos->add($articulo);
			}			
		}	
			
		//Crear ItemAlbaran y añadir al Albaran
		foreach($articulos as $articulo){
			$item_albaran = new ItemAlbaran();
			
			if($articulo instanceof Ejemplar){
				$item_albaran->setEjemplar($articulo);
			}
			elseif($articulo instanceof Articulo){
				$item_albaran->setArticulo($articulo);
			}
			
			$item_albaran->setDescuento($desc);
			
			$albaran->addItem($item_albaran);
			
			$em->persist($item_albaran);
		}
		
		$em->flush();
	}
}
?>