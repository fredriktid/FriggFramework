<?php

namespace Demo\Helper;

use Frigg\Helper\BaseHelper;

class ProfileHelper extends BaseHelper
{
	// just an example
    public function rankProfilesSince($start = false)
    {
    	$start = (!$start ? strtotime('-7 days') : (int) $start);
        // and then more logic...
    }
}
