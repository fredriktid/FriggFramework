<?php

namespace Frigg\Core\Component;

interface RequestComponentInterface
{
   public function getController();

    public function getQuery();

    public function parseRequest(HttpComponentInterface $http);
}
