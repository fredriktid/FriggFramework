<?php

namespace Frigg\Core;

defined('APP_TOKEN') or die('This file can not be called directly');

// class pattern functions
// keeps a concise relationship between class names and their identifiers
class ClassPattern
{
    // convert camelcase to identifier
    public static function classToIdentifier($class)
    {
        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $class, $matches);
        return implode('_', array_map('strtolower', $matches));
    }

    // convert identifier to camelcase
    public static function identifierToClass($identifier)
    {
        return implode(array_map('ucfirst', explode('_', $identifier)));
    }
}
