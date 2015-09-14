<?php
namespace Zorca;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;
class Zorca {
    public function __construct() {
        $extConfig = $this->loadConfig(BASE . 'ext/ext.json', []);
        foreach ($extConfig as $extConfigItem) {
            $extClass = BASE . 'ext' . DS . $extConfigItem['extKey'] . DS . $extConfigItem['extKey'] . '.php';
            if (file_exists($extClass)) require_once($extClass);
        }
        $request = Request::createFromGlobals();
        $routes = new Routing\RouteCollection();
        foreach ($extConfig as $extConfigItem) {
            $routes->add($extConfigItem['extKey'], new Routing\Route($extConfigItem['extSlug'] . '/{extAction}', ['extAction'=> 'default']));
        }
        $context = new Routing\RequestContext();
        $context->fromRequest($request);
        try {
            $matcher = new Routing\Matcher\UrlMatcher($routes, $context);
            $matchResult = $matcher->match($request->getPathInfo());
            $extClass = 'Zorca\Ext\\' . ucfirst($matchResult['_route']) . 'Ext';
            $extController = new $extClass;
            $response = new Response($extController->run($matchResult['extAction']));
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Страница не найдена', 404);
        } catch (\Exception $e) {
            $response = new Response('Ошибка системы ' . $e, 500);
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