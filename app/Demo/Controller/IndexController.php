<?php

namespace Demo\Controller;

use Frigg\Controller\ControllerBase;

class IndexController extends ControllerBase
{
    public function indexAction($request)
    {
        $template = $this->registry->getComponent('frigg/loader')->getInstance('frigg/twig');
        return $template->render('index.html.twig', array(
            'registry' => print_r($this->registry, true)
        ));
    }
}
