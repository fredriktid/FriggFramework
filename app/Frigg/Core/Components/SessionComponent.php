<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class SessionComponent extends BaseComponent
{
	public function start()
	{
        session_start();
        return $this;
    }

}
