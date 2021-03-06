<?php

namespace Frigg\Helper;

class ArrayHelper extends HelperBase
{
    public static function searchRecusively($array, $value)
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
