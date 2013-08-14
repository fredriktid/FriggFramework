<?php

namespace Frigg\Helper;

use \Frigg\Core\Registry;
use Frigg\Core\Exception\ErrorException;

class BaseHelper
{
	protected $registry = null;
	protected $data = array();

	public function __construct(Registry $registry)
	{
		$this->registry = $registry;
	}
}
