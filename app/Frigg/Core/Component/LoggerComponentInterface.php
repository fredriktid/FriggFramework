<?php

namespace Frigg\Core\Component;

interface LoggerComponentInterface
{
    public function setFile($file);

    public function getFile();

    public function write($message);
}
