<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request = Request::createFromGlobals();
$input = $request->get('name', 'Default');

$response = new Response;
$response->setContent(sprintf('Hello, %s', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));
$response->prepare($request);
$response->send();