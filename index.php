<?php
require_once __DIR__ . '/init.php';

$routeMap = array(
    '/hello' => 'hello',
    '/bye'   => 'bye',
);

$path = $request->getPathInfo();
if (isset($routeMap[$path])) {
    ob_start();
    extract($request->query->all(), EXTR_SKIP);
    include sprintf('%s.php', $routeMap[$path]);
    $response->setContent(ob_get_clean());
} else {
    $response->setStatusCode(404);
    $response->setContent('Страница не найдена');
}
$response->prepare($request);
$response->send();