<?php

namespace Frigg\Core\Loader;

use Frigg\Core\Registry;
use Frigg\Helper\FileHelper;
use Frigg\Helper\GlobalHelper;
use Frigg\Core\Exception\ErrorException;

class TwigLoader extends LoaderBase implements LoaderInterface
{
    public function __construct(Registry $registry)
    {
        parent::__construct($registry);
        $this->instance = $this->loadInstance();
    }

    // set twig environment instance
    public function loadInstance()
    {
        $skinPath = sprintf('%s/%s/%s', $this->registry->getSetting('path/design'), APP_SKIN, 'templates');
        if(!is_readable($skinPath)) {
            if(!FileHelper::createDir($skinPath)) {
                throw new ErrorException(sprintf('Template location not reachable: %s', $skinPath));
            }
        }

        // development mode
        if(GlobalHelper::isDevMode()) {
            $cachePath = false;
        } else {
            $cachePath = $this->registry->getSetting('path/cache');
            if(!FileHelper::createDir($cachePath)) {
                throw new ErrorException(sprintf('Cache directory not writable: %s', $cachePath));
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
