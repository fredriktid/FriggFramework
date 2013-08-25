<?php

namespace Demo\Helper;

use Frigg\Helper\HelperBase;

class AccountHelper extends HelperBase
{
	// just an example
    public static function generateGraphForAccountsCreatedBetween($start, $stop = false)
    {
    	$start = (int) $start;
    	$stop = (!$stop ? time() : (int) $stop);
        // and more stuff...
    }
}
