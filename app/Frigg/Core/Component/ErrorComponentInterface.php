<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\ExceptionInterface;

interface ErrorComponentInterface
{
    public function addError($message);

    public function exceptionString(ExceptionInterface $e);

    public function exceptionHandler(ExceptionInterface $e);
}
