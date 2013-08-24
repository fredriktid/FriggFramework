<?php

namespace Frigg\Core\Component;

interface ResponseComponentInterface
{
    public function executeRouter(ResponseComponentInterface $request);
}
