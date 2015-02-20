<?php

namespace Akuma\Bundle\BootswatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AkumaBootswatchBundle:Default:index.html.twig', array(
            'fa' => $this->container->getParameter('akuma_bootswatch.font_awesome')
        ));
    }
}
