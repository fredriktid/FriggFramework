<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\ErrorException;

class ArrayHelper extends BaseHelper
{
    public function searchRecusively($array, $value)
    {
        $found = array();
        array_walk_recursive($array, function($item, $key) use ($value, &$found) {
            if($value === $key) {
                $found[] = $item;
            }
        });

        return $found;
    }
}
