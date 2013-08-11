<?php

namespace Demo\Controller;

use Frigg\Controller\BaseController;
use Frigg\Core\Exception\ControllerException;

class IndexController extends BaseController
{
    public function indexAction($request)
    {
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();
        return $tpl->render('index.html.twig');
    }
}
