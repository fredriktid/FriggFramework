<?php

namespace Frigg\Core\Engine;

use Frigg\Core\Exception\EngineException;

defined('APP_TOKEN') or die('This file can not be called directly');

class TwigEngine extends BaseEngine
{
    public function getSkin()
    {
        $this->registry->getSetting('frigg_skin');
    }

    // set another skin
    public function setSkin($skinName)
    {
        $this->registry->setSetting('frigg_skin', $skinName);
        return $this;
    }

    // get twig instance
    protected function getEnvironment()
    {
        // path to skin templates
        $designPath = sprintf('%s/%s/%s', $this->registry->getSetting('frigg_path_design'), $this->registry->getSetting('frigg_skin'), 'templates');
        if(!is_readable($designPath)) {
            throw new EngineException(sprintf('Template location not readable: %s', $designPath));
        }

        // switch caching on/off in prod/dev environments
        if($this->registry->getHelper('frigg_global')->isDevMode()) {
            $cachePath = false;
        } else {
            $fileHelper = $this->registry->getHelper('frigg_file');
            $cachePath = $this->registry->getSetting('frigg_path_cache');
            if(!$fileHelper->createDir($cachePath)) {
                throw new EngineException(sprintf('Twig cache directory not writable: %s', $cachePath));
            }
        }

        // return environment instance
        $twigLoader = new \Twig_Loader_Filesystem($designPath);
        $twig = new \Twig_Environment($twigLoader, array(
            'cache' => $cachePath,
            'debug' => true, // provides "dump()" and also other useful functions
        ));
        $twig->addExtension(new \Twig_Extension_Debug());
        return $twig;
    }
}
