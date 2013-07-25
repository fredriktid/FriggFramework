<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class TemplateComponent extends BaseComponent
{
    // instantiate a template engine
    public function factory($engine = false)
    {
        if(!$engine)
        {
            $engine = $this->instance->getSetting('tpl');
        }

        $pattern = sprintf('load%s', $this->instance->pattern($engine));
        if(!method_exists(__CLASS__, $pattern))
        {
            throw new \Exception(sprintf('Unknown template engine: %s', $engine));
        }
        
        return call_user_func(array(__CLASS__, $pattern));
    }

    // twig loader
    private function loadTwig()
    {
        $designPath = sprintf('%s/%s/%s', APP_DESIGN, $this->instance->getSetting('skin'), 'templates');
        if(!is_readable($designPath))
        {
            throw new \Exception('Design not found');
        }

        $cacheKey = (defined('APP_DEV') && APP_DEV) ? false : APP_CACHE . '/twig';
        $twigLoader = new \Twig_Loader_Filesystem($designPath);
        return new \Twig_Environment($twigLoader, array(
            'cache' => $cacheKey
        ));
    }
}
