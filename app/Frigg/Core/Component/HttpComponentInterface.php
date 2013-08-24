<?php

namespace Frigg\Core\Component;

interface HttpComponentInterface
{
    public function queryString();

    public function getCookie($key);

    public function setCookie($key, $value, $expire = false, $path = '/', $host = false);

    public function getCookieHost();

    public function postVariables($key = false);

    public function getVariables($key = false);

    public function sessionStart();

    public function sessionId();

    public function sessionRegenerate();

    public function sessionDestroy();

    public function sessionVariables($key = false);

}
