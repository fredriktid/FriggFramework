<?php

require_once __DIR__ . '/autoloader.php';

// execute app
$response = require_once APP_PATH . '/router.php';
echo $response;
