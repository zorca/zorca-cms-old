<?php
namespace Zorca;

use Twig_Loader_Filesystem;
use Twig_Environment;
use DebugBar\StandardDebugBar;

/**
 * Class Theme
 *
 * @package Zorca
 */
class Theme {
    /**
     * @param $menuContent
     * @param $pageContent
     * @param $extKey
     *
     * @return string
     */
    public function render($menuContent, $pageContent, $extKey) {
        $mainConfig = Config::load('app');
        if ($mainConfig['mode'] === 'development') {
            $debugbar = new StandardDebugBar();
            $debugbarRenderer = $debugbar->getJavascriptRenderer();
            $debugbarHead = $debugbarRenderer->renderHead();
            $debugbarFoot = $debugbarRenderer->render();
        } else {
            $debugbarHead = '';
            $debugbarFoot = '';
        }
        $templates = new Twig_Loader_Filesystem(APP . 'design/themes' . DS . $mainConfig['theme'] . DS . 'templates/ext');
        $skeletons = new Twig_Loader_Filesystem(APP . 'design/skeletons/' . $mainConfig['skeleton']);
        $twigTemplate = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $skeleton = $twigSkeleton->loadTemplate($mainConfig['skeleton'] . '.twig');
        $renderedPage = $twigTemplate->render($extKey . '.twig', ['debugbarHead' => $debugbarHead, 'debugbarFoot' => $debugbarFoot, 'menuContent' => $menuContent, 'pageContent' => $pageContent, 'skeleton' => $skeleton]);
        return $renderedPage;
    }
}