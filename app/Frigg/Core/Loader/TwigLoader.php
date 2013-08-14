<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Exception\LoaderException;

defined('APP_TOKEN') or die('This file can not be called directly');

class TwigLoader extends BaseLoader
{
    public function getSkin()
    {
        $this->registry->getSetting('frigg/skin');
    }

    // set another skin
    public function setSkin($skinName)
    {
        $this->registry->setSetting('frigg/skin', $skinName);
        return $this;
    }

    // get twig instance
    protected function getEnvironment()
    {
        // path to skin templates
        $designPath = sprintf('%s/%s/%s', $this->registry->getSetting('frigg/path/design'), $this->registry->getSetting('frigg/skin'), 'templates');
        if(!is_readable($designPath)) {
            throw new LoaderException(sprintf('Template location not readable: %s', $designPath));
        }

        // switch caching on/off in prod/dev environments
        if($this->registry->getHelper('frigg/global')->isDevMode()) {
            $cachePath = false;
        } else {
            $fileHelper = $this->registry->getHelper('frigg/file');
            $cachePath = $this->registry->getSetting('frigg/path/cache');
            if(!$fileHelper->createDir($cachePath)) {
                throw new LoaderException(sprintf('Twig cache directory not writable: %s', $cachePath));
            }
        }

        // return environment instance
        $twigEngineLoader = new \Twig_Loader_Filesystem($designPath);
        $twigEngine = new \Twig_Environment($twigEngineLoader, array(
            'cache' => $cachePath,
            'debug' => true, // provides "dump()" and also other useful functions
        ));
        $twigEngine->addExtension(new \Twig_Extension_Debug());
        return $twigEngine;
    }
}
