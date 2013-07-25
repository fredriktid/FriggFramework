<?php

require_once __DIR__ . '/autoloader.php';

// execute app
$response = require_once APP_PATH . '/index.php';
echo $response;
