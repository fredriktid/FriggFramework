<?php

// your app
define('APP_NAME', 'Frigg');

// mode
define('APP_DEV', true);
define('APP_TOKEN', true);

// paths
define('APP_ROOT', dirname(__FILE__));
define('APP_HOME', APP_ROOT . '/app');
define('APP_PATH', APP_HOME . '/' . APP_NAME);
define('APP_DESIGN', APP_ROOT . '/design/' . APP_NAME);
define('APP_CACHE', APP_ROOT . '/cache/' . APP_NAME);