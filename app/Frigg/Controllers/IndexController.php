<?php

namespace Frigg\Controllers;

class IndexController extends BaseController
{
    public function indexAction($request)
    {
        return $this->tpl->render('index.html.twig', array('name' => 'Per'));
    }
}