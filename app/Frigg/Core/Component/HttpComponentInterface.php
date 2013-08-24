<?php

namespace Frigg\Core\Component;

interface HttpComponentInterface
{
    public static function queryString();

    public static function getCookie($key);

    public static function setCookie($key, $value, $expire = false, $path = '/', $host = false);

    public static function getCookieHost();

    public static function postVariables($key = false);

    public static function getVariables($key = false);

    public static function sessionStart();

    public static function sessionId();

    public static function sessionRegenerate();

    public static function sessionDestroy();

    public static function sessionVariables($key = false);
}
