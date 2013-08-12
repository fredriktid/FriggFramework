<?php

namespace Frigg\Helper;

use \Frigg\Core\Registry;

class BaseHelper
{
	protected $registry = null;
	protected $data = array();

	public function __construct(Registry $registry)
	{
		$this->registry = $registry;
	}

	protected function setData($key, $value)
	{
		$this->data[$key] = $value; 
	}

	protected function data(Array $data)
	{
		$this->data = $data;
	}
}
