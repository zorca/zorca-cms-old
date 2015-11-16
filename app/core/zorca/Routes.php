<?php
    namespace Zorca;

    use Symfony\Component\Routing;

    class Routes
    {
        static function load() {
            $routes = new Routing\RouteCollection();
            $routesConfig = Config::load('ext');
            foreach ($routesConfig as $routesConfigItem) {
                if ($routesConfigItem['extType'] === 'component') $routes->add($routesConfigItem['extKey'], new Routing\Route($routesConfigItem['extSlug'] . '/{extAction}', ['extAction' => 'index'], ['extAction' => '^[a-z0-9-]+']));
            }
            return $routes;
        }
    }