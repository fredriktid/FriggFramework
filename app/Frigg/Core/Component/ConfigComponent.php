<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\CoreException;

defined('APP_TOKEN') or die('This file can not be called directly');

class ConfigComponent extends BaseComponent
{
    protected $config = array();

    public function setConfig($identifier, $filePath)
    {
        if(!array_key_exists($identifier, $this->config)) {
            if(is_readable($filePath)) {
                $this->config[$identifier] = require $filePath;
            }
        }
        return $this;
    }

    public function getConfig($identifier)
    {
        // first item is filename
        $parts = explode('/', $identifier);
        $fileName = trim(array_shift($parts));
        $filePath = sprintf('%s/%s.php', $this->registry->getSetting('frigg/path/config'), $fileName);

        // read config
        $this->setConfig($identifier, $filePath);

        // check if config is available
        if(!array_key_exists($identifier, $this->config)) {
            throw new CoreException(sprintf('Config "%s" does not exist in "%s"', $identifier, $filePath));
        }

        // match config with identifier
        $configContent = $this->config[$identifier];
        foreach($parts as $i => $key) {
            if(is_array($configContent)) {
                if(array_key_exists($key, $configContent)) {
                    $configContent = $configContent[$key];
                } else {
                    $configContent = null;
                }
            }
        }

        return $configContent;
    }
}
