<?php

namespace Demo\Helper;

use Frigg\Helper\BaseHelper;

class AccountHelper extends BaseHelper
{
	// just an example
    public function generateGraphForAccountsCreatedBetween($start, $stop = false)
    {
    	$start = (int) $start;
    	$stop = (!$stop ? time() : (int) $stop);
        // and then more logic...
    }
}
