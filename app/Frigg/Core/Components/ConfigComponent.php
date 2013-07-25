<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class ConfigComponent extends BaseComponent
{
    protected static $configs = array();

    public function __construct($instance)
    {
        parent::__construct($instance);

        foreach(glob(APP_CONFIG . '/*.php') as $file) {
            $fileObj = new \SplFileObject($file);
            $fileName = substr($fileObj->getFilename(), 0, -1 * (strlen($fileObj->getExtension()) + 1));
            static::$configs[$fileName] = require $file;
        }
    }

    public function getConfig($config, $section = false)
    {
        if(!array_key_exists($config, static::$configs)) {
            return false;
        }

        if(!$section) {
            return static::$configs[$config];
        }

        return static::$configs[$config][$section];
    }
}
