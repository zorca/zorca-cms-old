<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use ParsedownExtra;
use Zorca\Theme;
use Zorca\Scss;
class PagesExt {
    public function run($extRequest, $extAction) {
        $responseStatus = '200';
        $parsedown = new ParsedownExtra();
        $pageContentFilePath = DATA . 'ext/components/pages' . DS . $extAction . '.md';
        if (!file_exists($pageContentFilePath)) {
            $pageContentFilePath = DATA . 'ext/components/pages/404.md';
            $responseStatus = '404';
        }
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $menu = new Menu();
        $menuContent = $menu->load('menuMain');
        $scss = new Scss();
        $scss->setImportPaths([BASE . 'app/design/themes/default/styles', BASE . 'app/core/oxi', BASE . 'app/design/skeletons']);
        $scss->compileFile([BASE. 'app/design/themes/default/styles/main.scss'], BASE. 'pub/styles/main.css');
        $theme = new Theme();
        $renderedPage = $theme->render($menuContent, $pageContent, 'pages');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}