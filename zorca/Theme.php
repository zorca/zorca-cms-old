<?php
namespace Zorca;

use Twig_Loader_Filesystem;
use Twig_Environment;
class Theme {
    public function render($pageContent) {
        $mainConfig = Config::load('app');
        $templates = new Twig_Loader_Filesystem(BASE . 'pub/themes' . DS . $mainConfig['theme'] . DS . 'templates');
        $skeletons = new Twig_Loader_Filesystem(BASE . 'pub/skeletons');
        $twigTemplate = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $skeleton = $twigSkeleton->loadTemplate($mainConfig['skeleton'] . '.twig');
        $renderedPage = $twigTemplate->render('pages.twig', ['pageContent' => $pageContent, 'skeleton' => $skeleton]);
        return $renderedPage;
    }
}