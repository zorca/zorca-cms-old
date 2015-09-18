<?php
namespace Zorca;

use Twig_Loader_Filesystem;
use Twig_Environment;
use DebugBar\StandardDebugBar;
class Theme {
    public function render($menuContent, $pageContent) {
        $mainConfig = Config::load('app');
        $debugbar = new StandardDebugBar();
        $debugbarRenderer = $debugbar->getJavascriptRenderer();
        if ($mainConfig['mode'] === 'development') {
            $debugbarHead = $debugbarRenderer->renderHead();
            $debugbarFoot = $debugbarRenderer->render();
        } else {
            $debugbarHead = '';
            $debugbarFoot = '';
        }
        $templates = new Twig_Loader_Filesystem(APP . 'design/themes' . DS . $mainConfig['theme'] . DS . 'templates/ext');
        $skeletons = new Twig_Loader_Filesystem(APP . 'design/skeletons/default');
        $twigTemplate = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $skeleton = $twigSkeleton->loadTemplate($mainConfig['skeleton'] . '.twig');
        $renderedPage = $twigTemplate->render('pages.twig', ['debugbarHead' => $debugbarHead, 'debugbarFoot' => $debugbarFoot, 'menuContent' => $menuContent, 'pageContent' => $pageContent, 'skeleton' => $skeleton]);
        return $renderedPage;
    }
}