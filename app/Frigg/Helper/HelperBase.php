<?php

namespace Frigg\Helper;

use Frigg\Core\Registry;

abstract class HelperBase
{
	protected $registry = null;
	protected $data = array();

	public function __construct(Registry $registry)
	{
		$this->registry = $registry;
	}
}
