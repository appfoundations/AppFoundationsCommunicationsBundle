<?php

namespace AppFoundations\CommunicationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppFoundationsCommunicationsBundle:Default:index.html.twig');
    }
}
