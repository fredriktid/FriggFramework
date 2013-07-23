<?php

namespace Frigg\Controllers;

class BaseController
{
	protected $tpl;

	public function __construct()
	{
		$registry = \Frigg\Core\Registry::singleton();
		$this->tpl = $registry->getComponent('tpl')->factory();
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