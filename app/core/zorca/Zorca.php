<?php
namespace Zorca;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;

/**
 * Class Zorca
 *
 * @package Zorca
 */
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
            $scss = new Scss();
            if ($matchResult['_route'] === 'admin') {
                $scss->setImportPaths([
                    BASE . 'app/core/oxi',
                    BASE . 'app/ext/components/admin/design/skeletons',
                    BASE . 'app/ext/components/admin/design/themes/default/styles']);
                $scss->compileFile([
                    BASE . 'app/ext/components/admin/design/themes/default/styles/main.scss'],
                    BASE . 'pub/styles/admin.css');
            } else {
                $scss->setImportPaths([
                    BASE . 'app/core/oxi',
                    BASE . 'app/design/skeletons',
                    BASE . 'app/design/themes/default/styles']);
                $scss->compileFile([
                    BASE . 'app/design/themes/default/styles/main.scss'],
                    BASE . 'pub/styles/main.css');
            }
            $extClass = 'Zorca\Ext\\' . ucfirst($matchResult['_route']) . 'Ext';
            $extController = new $extClass;
            $response = $extController->run($request, $matchResult['extAction']);
        } catch (Routing\Exception\ResourceNotFoundException $e) {
            $response = new Response('Расширение не найдено ' . $e, 404);
        } catch (\Exception $e) {
            $response = new Response('Ошибка системы ' . $e->getMessage(), 500);
        }
        $response->prepare($request);
        $response->send();
    }
}