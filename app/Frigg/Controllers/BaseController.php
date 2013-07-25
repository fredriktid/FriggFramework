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
		$registry = App\Registry::singleton()
			->setComponent('http', 'http')
			->setComponent('log', 'logger')
			->setComponent('session', 'session');

		$this->tpl = $registry->getComponent('tpl')->setEngine('twig')->load();
		$this->http = $registry->getComponent('http');
		$this->log = $registry->getComponent('log');
		$this->session = $registry->getComponent('session');

	}
}
