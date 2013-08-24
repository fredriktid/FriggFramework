<?php

namespace Frigg\Core\Component;

interface ConfigComponentInterface
{
    public function setSection($identifier, $filePath);

    public function getSection($identifier);

}