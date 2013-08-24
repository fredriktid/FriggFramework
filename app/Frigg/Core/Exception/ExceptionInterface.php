<?php

namespace Frigg\Core\Exception;

interface ExceptionInterface
{
    public function getMessage();

    public function getTrace();

    public function getTraceAsString();

    public function getCode();

    public function getPrevious();

    public function getLine();
}
