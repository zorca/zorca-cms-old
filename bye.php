<?php
require_once __DIR__ . '/init.php';

$response->setContent('GoodBye');
$response->prepare($request);
$response->send();