<?php

namespace Frigg\Core;

interface RegistryInterface
{
    public static function singleton();

    public function getComponent($identifier);

    public function setComponent($identifier);

    public function getHelper($identifier);

    public function setSetting($key, $value);

    public function getSetting($key);
}
