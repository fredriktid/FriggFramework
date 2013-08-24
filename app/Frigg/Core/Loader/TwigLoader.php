<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Registry;
use Frigg\Core\Exception\ErrorException;

class TwigLoader extends LoaderBase implements LoaderInterface
{
    public function __construct(\Frigg\Core\Registry $registry)
    {
        parent::__construct($registry);
        $this->instance = $this->loadInstance();
    }

    // set twig environment instance
    public function loadInstance()
    {
        $skinPath = sprintf('%s/%s/%s', $this->registry->getSetting('frigg/path/design'), $this->registry->getSetting('frigg/skin'), 'templates');
        if(!is_readable($skinPath)) {
            $fileHelper = $this->registry->getHelper('frigg/file');
            if(!$fileHelper->createDir($skinPath)) {
                throw new ErrorException(sprintf('Template location not reachable: %s', $skinPath));
            }
        }

        // development mode
        if($this->registry->getHelper('frigg/global')->isDevMode()) {
            $cachePath = false;
        } else {
            $fileHelper = $this->registry->getHelper('frigg/file');
            $cachePath = $this->registry->getSetting('frigg/path/cache');
            if(!$fileHelper->createDir($cachePath)) {
                throw new ErrorException(sprintf('Twig cache directory not writable: %s', $cachePath));
            }
        }

        // return environment instance
        $twigLoader = new \Twig_Loader_Filesystem($skinPath);
        $twigInstance = new \Twig_Environment($twigLoader, array(
            'cache' => $cachePath,
            'debug' => true, // provides "dump()" and also other useful functions
        ));
        $twigInstance->addExtension(new \Twig_Extension_Debug());

        // save and return new twig instance
        return $twigInstance;
    }
}
