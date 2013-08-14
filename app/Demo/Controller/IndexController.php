<?php

namespace Demo\Controller;

use Frigg\Controller\BaseController;
use Frigg\Core\Exception\ControllerException;

class IndexController extends BaseController
{
    public function indexAction($request)
    {
        $twigEngine = $this->registry->getComponent('frigg/loader')->getLoader('frigg/twig')->getInstance();
        return $twigEngine->render('index.html.twig');
    }
}
