<?php
require_once __DIR__ . '/init.php';

$routeMap = array(
    '/hello' => __DIR__.'/hello.php',
    '/bye'   => __DIR__.'/bye.php',
);

$path = $request->getPathInfo();
if (isset($routeMap[$path])) {
    require $routeMap[$path];
} else {
    $response->setStatusCode(404);
    $response->setContent('Not Found');
}
$response->prepare($request);
$response->send();