<?php

require_once __DIR__ . '/autoloader.php';

// execute app
$response = require_once  __DIR__ . '/app/router.php';
echo $response;
