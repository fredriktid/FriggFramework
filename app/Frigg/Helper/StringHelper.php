<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\TypeException;

class StringHelper extends HelperBase
{
    // escape a string to used it as a regexp pattern
    public static function escapeGrepPattern($pattern)
    {
        return addcslashes($pattern, './+*?[^]($)');
    }

    // "get string [between] two characters"
    public static function getStringBetween($string, $start, $end)
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
    public static function startsWith($haystack, $needle)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    // string ends with ...
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    // is string serialized?
    public static function isSerialized($string)
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
