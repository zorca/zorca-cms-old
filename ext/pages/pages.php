<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use ParsedownExtra;
use Zorca\Theme;
class PagesExt {
    public function run($extAction) {
        $responseStatus = '200';
        $parsedown = new ParsedownExtra();
        $pageContentFilePath = BASE . 'data/pages' . DS . $extAction . '.md';
        if (!file_exists($pageContentFilePath)) {
            $pageContentFilePath = BASE . 'data/pages/404.md';
            $responseStatus = '404';
        }
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $theme = new Theme();
        $renderedPage = $theme->render($pageContent);
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}