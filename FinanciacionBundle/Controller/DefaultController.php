<?php

namespace RGM\eLibreria\FinanciacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RGMELibreriaFinanciacionBundle:Default:index.html.twig', array('name' => $name));
    }
}
