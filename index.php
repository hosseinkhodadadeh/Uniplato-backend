<?php
require_once './vendor/autoload.php';

use App\Lib\RequestHandler;

define('ROOT', __DIR__);
$handler = new RequestHandler();
$request = new \App\Lib\Request($_GET, $_POST, $_SERVER);
$response = $handler->handle($request);
$response->send();