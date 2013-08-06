<?php

namespace Demo\Controllers;

use Frigg\Controllers\BaseController;

class IndexController extends BaseController
{
    public function indexAction($request)
    {
        return $this->tpl->render('index.html.twig');
    }
}
