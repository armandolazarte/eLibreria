<?php

namespace RGM\eLibreria\VentasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RGMELibreriaVentasBundle:Default:index.html.twig', array('name' => $name));
    }
}
