<?php
require_once __DIR__ . '/init.php';

$input = $request->get('name', 'Default');

$response->setContent(sprintf('Hello, %s', htmlspecialchars($input, ENT_QUOTES, 'UTF-8')));
$response->prepare($request);
$response->send();