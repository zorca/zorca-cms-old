<?php
namespace Zorca;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
class Zorca {
    public function __construct() {
        $request = Request::createFromGlobals();
        $routes = new Routing\RouteCollection();
        $extConfig = $this->loadConfig(BASE . 'ext/ext.json', []);
        foreach ($extConfig as $extConfigItem) {
            $routes->add($extConfigItem['extName'], new Routing\Route($extConfigItem['extSlug']));
        }
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
        try {
            $matchResult = $matcher->match($request->getPathInfo());
            $response = new Response('Расширение = '.$matchResult['_route']);
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Страница не найдена', 404);
        } catch (\Exception $e) {
            $response = new Response('Обнаружена ошибка системы', 500);
        }
        $response->prepare($request);
        $response->send();
    }
    private function loadConfig($configFilePath, $configDefault = []) {
        $config = $configDefault;
        if (file_exists($configFilePath)) $config = json_decode(file_get_contents($configFilePath), true);
        return $config;
    }
}