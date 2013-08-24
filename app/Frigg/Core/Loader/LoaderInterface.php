<?php

namespace Frigg\Core\Loader;

interface LoaderInterface
{
    public function getInstance();

    public function loadInstance();
}
