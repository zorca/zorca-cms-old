<?php
namespace Zorca;

use Twig_Loader_Filesystem;
use Twig_Environment;
use DebugBar\StandardDebugBar;
class Theme {
    public function render($menuContent, $pageContent) {
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
        $renderedPage = $twigTemplate->render('pages.twig', ['debugbarHead' => $debugbarHead, 'debugbarFoot' => $debugbarFoot, 'menuContent' => $menuContent, 'pageContent' => $pageContent, 'skeleton' => $skeleton]);
        return $renderedPage;
    }
}