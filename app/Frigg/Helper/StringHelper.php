<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\TypeException;

class StringHelper extends BaseHelper
{
    // escape a string to used it as a regexp pattern
    public function escapeGrepPattern($pattern)
    {
        return addcslashes($pattern, './+*?[^]($)');
    }

    // "get string [between] two characters"
    public function getStringBetween($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if($ini == 0) {
            return '';
        }

        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    // string starts with ...
    public function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    // string ends with ...
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    // is string serialized?
    public function isSerialized($string)
    {
        if(!is_string($string)) {
            return false;
        }

        $string = trim($string);
        if('N;' == $string) {
            return true;
        }

        if(!preg_match( '/^([adObis]):/', $string, $matches)) {
            return false;
        }

        switch($matches[1]) {
            case 'a':
            case 'O':
            case 's':
                if(preg_match( "/^{$matches[1]}:[0-9]+:.*[;}]\$/s", $string)) {
                    return true;
                }
                break;
            case 'b':
            case 'i':
            case 'd':
                if(preg_match( "/^{$matches[1]}:[0-9.E-]+;\$/", $string)) {
                    return true;
                }
                break;
        }

        return false;
    }
}
