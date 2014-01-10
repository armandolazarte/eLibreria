<?php

namespace RGM\eLibreria\VentasBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemVenta
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemVenta
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
	 * @ORM\ManyToOne(targetEntity="RGM\eLibreria\VentasBundle\Entity\Venta", inversedBy="items")
	 * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
	 */
    private $venta;

    /**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\LibroBundle\Entity\Ejemplar", inversedBy="itemVenta")
	 * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
	 */
    private $ejemplar;

    /**
	 * @ORM\OneToOne(targetEntity="RGM\eLibreria\SuministroBundle\Entity\Articulo", inversedBy="itemVenta")
	 * @ORM\JoinColumn(name="articulo_id", referencedColumnName="ref", nullable=true, onDelete="CASCADE")
	 */
    private $articulo;

    /**
	 * @ORM\Column(name="descuento", type="float", nullable=false)
	 */
    private $descuento = 0;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set venta
     *
     * @param string $venta
     * @return ItemVenta
     */
    public function setVenta($venta)
    {
        $this->venta = $venta;
    
        return $this;
    }

    /**
     * Get venta
     *
     * @return string 
     */
    public function getVenta()
    {
        return $this->venta;
    }

    /**
     * Set ejemplar
     *
     * @param string $ejemplar
     * @return ItemVenta
     */
    public function setEjemplar($ejemplar)
    {
        $this->ejemplar = $ejemplar;
    
        return $this;
    }

    /**
     * Get ejemplar
     *
     * @return string 
     */
    public function getEjemplar()
    {
        return $this->ejemplar;
    }

    /**
     * Set articulo
     *
     * @param string $articulo
     * @return ItemVenta
     */
    public function setArticulo($articulo)
    {
        $this->articulo = $articulo;
    
        return $this;
    }

    /**
     * Get articulo
     *
     * @return string 
     */
    public function getArticulo()
    {
        return $this->articulo;
    }

	public function getElemento() {
		$res = null;

		if ($this->ejemplar) {
			$res = $this->ejemplar;
		}

		if ($this->articulo) {
			$res = $this->articulo;
		}

		return $res;
	}

    /**
     * Set descuento
     *
     * @param float $descuento
     * @return ItemVenta
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    
        return $this;
    }

    /**
     * Get descuento
     *
     * @return float 
     */
    public function getDescuento()
    {
        return $this->descuento;
    }
}
