<?php

namespace Frigg\Helper;

use Frigg\Core\Registry;
use Frigg\Core\Exception\ErrorException;

abstract class BaseHelper
{
	protected $registry = null;
	protected $data = array();

	public function __construct(\Frigg\Core\Registry $registry)
	{
		$this->registry = $registry;
	}
}
