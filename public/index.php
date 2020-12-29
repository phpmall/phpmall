<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$response = $app->http->run();

$response->send();

$app->http->end($response);
