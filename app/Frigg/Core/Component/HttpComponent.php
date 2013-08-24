<?php

namespace Frigg\Core\Component;

class HttpComponent extends ComponentBase implements ComponentInterface, HttpComponentInterface
{
    public static function redirect($target)
    {
        header(sprintf('Location: %s', $target));
    }

    public static function serverData($key)
    {
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : false;
    }

    public static function queryString()
    {
        return static::serverData('QUERY_STRING');
    }

    public static function httpHost()
    {
         return static::serverData('HTTP_HOST');
    }

    public static function remoteAddr()
    {
        return static::serverData('REMOTE_ADDR');
    }

    public static function sessionStart()
    {
        session_start();
    }

    public static function sessionId()
    {
        session_id();
    }

    public static function sessionRegenerate()
    {
        session_regenerate_id();
    }

    public static function sessionDestroy()
    {
        session_destroy();
    }

    public static function sessionVariables($key = false)
    {
        if(!$key) {
            return $_SESSION;
        }

        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    public static function setSessionVariable($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function postVariables($key = false)
    {
        if(!$key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : false;
    }

    public static function setPostVariable($key, $value)
    {
        $_POST[$key] = $value;
        return $this;
    }

    public static function getVariables($key = false)
    {
        if(!$key) {
            return $_GET;
        }

        return (isset($_GET[$key])) ? $_GET[$key] : false;
    }

    public static function setGetVariables($key, $value)
    {
        $_GET[$key] = $value;
        return $this;
    }

    public static function getCookie($key)
    {
        if(!isset($_COOKIE[$key])) {
            return false;
        }

        $value = $_COOKIE[$key];
        $stringHelper = static::registry->getHelper('frigg/string');

        if($fileHelper->isSerialized($value)) {
            $value = unserialize($value);
        }

        return $value;
    }

    public static function setCookie($key, $value, $expire = false, $path = '/', $host = false)
    {
        if(is_array($value)) {
            $value = serialize($value);
        }

        if(!$expire) {
            $expire = strtotime('+1 week');
        }

        if(!$host) {
            $host = static::getCookieHost();
        }

        setcookie($key, $value, $expire, $path, $host);
        return $this;
    }

    public static function getCookieHost()
    {
        $host = static::httpHost();

        // fix the host to accept hosts with and without 'www.'
        if(strtolower(substr($host, 0, 4)) == 'www.') {
            $host = substr($host, 4);
        }

        // add the dot prefix to ensure compatibility with subdomains
        if(substr($host, 0, 1) != '.') {
            $host = sprintf('.%s', $host);
        }

        // remove port information
        $port = strpos($host, ':');
        if($port !== false) {
            $host = substr($host, 0, $port);
        }

        return $host;
    }

}
