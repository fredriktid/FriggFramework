<?php

namespace TestApp\Controllers;

use Frigg\Controllers\BaseController;

class IndexController extends BaseController
{
	public function indexAction($require)
	{
        return $this->tpl->render('index.html.twig');
	}
}
