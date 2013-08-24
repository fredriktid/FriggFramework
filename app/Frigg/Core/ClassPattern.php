<?php

namespace Frigg\Core;

use Frigg\Core\Exception\TypeException;
use Frigg\Core\Exception\ErrorException;

defined('APP_TOKEN') or die('This file can not be called directly');

// class pattern functions
// converts between identifiers and class names
class ClassPattern
{
    // convert snakecase to camelcase
    public static function snakeToCamel($identifier)
    {
        return trim(implode(array_map('ucfirst', explode('_', strtolower($identifier)))));
    }

    // convert camelcase to snakecase
    public static function classToIdentifier($classString)
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $classString, $matches, PREG_PATTERN_ORDER);
        if(!is_array($matches[0]) || !count($matches[0])) {
            throw new TypeException(sprintf('%s: Error converting %s to identifier', __METHOD__, $classString));
        }

        return implode('/', array_map('strtolower', $matches[0]));
    }

    // converts and separates appName from className
    // returns array with (appName, className)
    public static function identifierToClass($identifier)
    {
        if(!is_string($identifier)) {
            throw new TypeException(sprintf('A string is required as identifier in %s', __METHOD__));
        }

        $parts = array_map(array(__CLASS__, 'snakeToCamel'), explode('/', $identifier));
        return array(array_shift($parts), implode($parts));
    }
}
