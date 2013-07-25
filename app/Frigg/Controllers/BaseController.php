<?php

namespace Frigg\Controllers;

use Frigg\Core as App;

class BaseController
{
	protected $tpl;
	protected $http;
	protected $session;

	public function __construct()
	{
		$registry = App\Registry::singleton();
		$registry->setComponent('http', 'http');
		$registry->setComponent('tpl', 'template');
		$registry->setComponent('session', 'session');

		$this->tpl = $registry->getComponent('tpl')->factory();
		$this->http = $registry->getComponent('http');
		$this->session = $registry->getComponent('session');
	}

	public function error($msg, $code = 500)
	{
		$msg = (!is_array($msg)) ? array($msg) : $msg;
		return $this->tpl->render('error.html.twig', array(
			'code' => $code,
			'errors' => $msg
			));
	}
}
