<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\ComponentException;

defined('APP_TOKEN') or die('This file can not be called directly');

class ConfigComponent extends BaseComponent
{
    protected $config = array();

    protected function readConfig($key)
    {
        // already read?
        if(isset($this->config[$key])) {
            return;
        }

        // config file path
        $filePath = sprintf('%s/%s.php', $this->registry->getSetting('frigg_path_config'), $key);
        if(!is_readable($filePath)) {
            throw new ComponentException(sprintf('Config file does not exist: %s', $filePath));
        }

        // save in object
        $this->config[$key] = require $filePath;
    }

    public function getConfig($key, $section = false)
    {
        $this->readConfig($key);

        if(!array_key_exists($key, $this->config)) {
            throw new ComponentException(sprintf('Config key %s does not exist', $key));
        }

        if(!$section) {
            return $this->config[$key];
        }

        if(!isset($this->config[$key][$section])) {
            throw new ComponentException(sprintf('Config section %s does not exist', $filePath));
        }

        return $this->config[$key][$section];
    }
}
