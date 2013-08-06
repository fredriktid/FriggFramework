<?php

namespace Frigg\Core\Components\TemplateEngines;

defined('APP_TOKEN') or die('This file can not be called directly');

class TwigEngine
{
    private $registry;
    public $instance;

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->instance = $this->getEnviornment();
    }

    public function getEnviornment()
    {
        $designPath = sprintf('%s/%s/%s', APP_DESIGN, $this->registry->getComponent('config')->getConfig('site', 'skin'), 'templates');
        if (!is_readable($designPath)) {
            throw new \Exception('Template location not found');
        }

        $cacheKey = (defined('APP_DEV') && APP_DEV) ? false : APP_CACHE . '/twig';
        $twigLoader = new \Twig_Loader_Filesystem($designPath);
        return new \Twig_Environment($twigLoader, array(
            'cache' => $cacheKey
        ));
    }
}
