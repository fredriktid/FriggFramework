<?php

namespace Frigg\Helper;

class BaseHelper
{
	protected $registry;

	public function __construct($registry)
	{
		$this->registry = $registry;
	}
}