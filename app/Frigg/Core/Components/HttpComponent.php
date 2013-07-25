<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class HttpComponent extends BaseComponent
{
    public function redirect($target)
    {
        header(sprintf('Location: %s', $target));
    }

    public function startSession()
    {
        session_start();
    }

    public function destroySession()
    {
        session_destroy();
    }

    public function getSessionId()
    {
        session_id();
    }

    public function regenerateSessionId()
    {
        session_regenerate_id();
    }

    public function getSession($key)
    {
        if(!$key) {
            return $_SESSION;
        }

        return $_SESSION[$key];
    }

    public function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function setPost($key, $value)
    {
        $_POST[$key] = $value;
        return $this;
    }

    public function getPost($key = false)
    {
        if(!$key) {
            return $_POST;
        }

        return $_POST[$key];
    }

    public function setGet($key, $value)
    {
        $_GET[$key] = $value;
        return $this;
    }

    public function getGet($key = false)
    {
        if(!$key) {
            return $_GET;
        }
        
        return $_GET[$key];
    }

    public function getRequest($key = false)
    {
        if(!$key) {
            return $_REQUEST;
        }
        
        return $_REQUEST[$key];
    }
}
