<?php
namespace Zorca;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
class Zorca {
    public function __construct() {
        Autoload::load();
        $routes = Routes::load();
        $request = Request::createFromGlobals();
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        try {
            $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
            $matchResult = $matcher->match($request->getPathInfo());
            $extClass = 'Zorca\Ext\\' . ucfirst($matchResult['_route']) . 'Ext';
            $extController = new $extClass;
            $response = $extController->run($matchResult['extAction']);
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Расширение не найдено', 404);
        } catch (\Exception $e) {
            $response = new Response('Ошибка системы ' . $e, 500);
        }
        $response->prepare($request);
        $response->send();
    }
}