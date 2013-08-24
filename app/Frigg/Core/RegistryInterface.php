<?php

namespace Frigg\Core;

interface RegistryInterface
{
    public static function singleton();

    public static function getComponent($identifier);

    public static function setComponent($identifier);

    public static function setSetting($key, $value);

    public static function getSetting($key);
}
