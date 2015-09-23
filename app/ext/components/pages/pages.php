<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use ParsedownExtra;
use Zorca\Template;
use Zorca\Scss;

/**
 * Class PagesExt
 *
 * @package Zorca\Ext
 */
class PagesExt {
    /**
     * @param $extRequest
     * @param $extAction
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function run($extRequest, $extAction) {
        $responseStatus = '200';
        $parsedown = new ParsedownExtra();
        $pageContentFilePath = DATA . 'ext/components/pages' . DS . $extAction . '.md';
        if (!file_exists($pageContentFilePath)) {
            $pageContentFilePath = APP . 'ext/components/pages/content/404.md';
            $responseStatus = '404';
        }
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $menuContent = MenuMod::load('pages', 'menuMain', 'horizontal');
        $renderedPage = Template::render(['menuContent' => $menuContent, 'pageContent' => $pageContent, 'skeleton' => 'default'], '', 'pages');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}