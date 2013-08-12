<?php

namespace Frigg\Core;

use Frigg\Core\Exception\ClassPatternException;

defined('APP_TOKEN') or die('This file can not be called directly');

// class pattern functions
// keeps a concise relationship between class names (camelcase) and their identifiers (snakecase)
class ClassPatternConverter
{
    // convert camelcase to snakecase
    public static function classStringToIdentifier($classString)
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $classString, $matches, PREG_PATTERN_ORDER);
        if(is_array($matches[0]) || !count($matches[0])) {
            throw new ClassPatternException(sprintf('%s: Error converting %s to identifier', $classString));
        }
        return implode('_', array_map('strtolower', $matches[0]));
    }

    // convert snakecase to camelcase
    public static function identifierToClassString($identifier)
    {
        if(!is_string($identifier)) {
            throw new ClassPatternException(sprintf('%s: Received identifier is not a string.', __METHOD__));
        }
        return implode(array_map('ucfirst', explode('_', $identifier)));
    }
}
