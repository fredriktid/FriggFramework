<?php

namespace Frigg\Core\Component;

class HttpComponent extends ComponentBase implements ComponentInterface, HttpComponentInterface
{
    public function redirect($target)
    {
        header(sprintf('Location: %s', $target));
    }

    public function serverData($key)
    {
        return (isset($_SERVER[$key])) ? $_SERVER[$key] : false;
    }

    public function queryString()
    {
        return $this->serverData('QUERY_STRING');
    }

    public function httpHost()
    {
         return $this->serverData('HTTP_HOST');
    }

    public function remoteAddr()
    {
        return $this->serverData('REMOTE_ADDR');
    }

    public function sessionStart()
    {
        session_start();
    }

    public function sessionId()
    {
        session_id();
    }

    public function sessionRegenerate()
    {
        session_regenerate_id();
    }

    public function sessionDestroy()
    {
        session_destroy();
    }

    public function sessionVariables($key = false)
    {
        if(!$key) {
            return $_SESSION;
        }

        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    public function setSessionVariable($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function postVariables($key = false)
    {
        if(!$key) {
            return $_POST;
        }

        return (isset($_POST[$key])) ? $_POST[$key] : false;
    }

    public function setPostVariable($key, $value)
    {
        $_POST[$key] = $value;
        return $this;
    }

    public function getVariables($key = false)
    {
        if(!$key) {
            return $_GET;
        }
        return (isset($_GET[$key])) ? $_GET[$key] : false;
    }

    public function setGetVariables($key, $value)
    {
        $_GET[$key] = $value;
        return $this;
    }

    public function getCookie($key)
    {
        if(!isset($_COOKIE[$key])) {
            return false;
        }

        $value = $_COOKIE[$key];
        $stringHelper = $this->registry->getHelper('frigg/string');

        if($fileHelper->isSerialized($value)) {
            $value = unserialize($value);
        }

        return $value;
    }

    public function setCookie($key, $value, $expire = false, $path = '/', $host = false)
    {
        if(is_array($value)) {
            $value = serialize($value);
        }

        if(!$expire) {
            $expire = strtotime('+1 week');
        }

        if(!$host) {
            $host = $this->getCookieHost();
        }

        setcookie($key, $value, $expire, $path, $host);
        return $this;
    }

    public function getCookieHost()
    {
        $host = $this->httpHost();

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
