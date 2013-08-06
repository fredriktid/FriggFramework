<?php

namespace Frigg\Models;

class BaseModel
{
	protected $registry;

	public function __construct($registry)
	{
		$this->registry = $registry;
	}
}