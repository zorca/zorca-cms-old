<?php
namespace Zorca;

use Twig_Loader_Filesystem;
use Twig_Environment;
use DebugBar\StandardDebugBar;

/**
 * Class Template
 *
 * @package Zorca
 */
class Template {
    /**
     * @param $content
     * @param $service
     * @param $extKey
     *
     * @return string
     */
    static function render($content, $service, $extKey) {
        $mainConfig = Config::load('app');
        $debugbarHead = '';
        $debugbarFoot = '';
        if ($mainConfig['mode'] === 'development') {
            $debugbar = new StandardDebugBar();
            $debugbarRenderer = $debugbar->getJavascriptRenderer();
            $debugbarHead = $debugbarRenderer->renderHead();
            $debugbarFoot = $debugbarRenderer->render();

        }
        if ($extKey === 'admin') {
            $adminDesignPath = APP . 'ext/components/admin' . DS . 'design';
            $templates = new Twig_Loader_Filesystem($adminDesignPath . DS . 'themes' . DS .  $mainConfig['themeAdmin'] . DS . 'templates');
            $skeletons = new Twig_Loader_Filesystem($adminDesignPath . DS . 'skeletons' . DS . $mainConfig['skeletonAdmin']);
            $twigTemplate = new Twig_Environment($templates);
            $twigSkeleton = new Twig_Environment($skeletons);
            $skeleton = $twigSkeleton->loadTemplate($mainConfig['skeletonAdmin'] . '.twig');
        } else {
            $extDesignPath = APP . 'design';
            $templates = new Twig_Loader_Filesystem($extDesignPath . DS . 'themes' . DS . $mainConfig['theme'] . DS . 'templates');
            $skeletons = new Twig_Loader_Filesystem($extDesignPath . DS . 'skeletons' . DS . $mainConfig['skeleton']);
            $twigTemplate = new Twig_Environment($templates);
            $twigSkeleton = new Twig_Environment($skeletons);
            $skeleton = $twigSkeleton->loadTemplate($mainConfig['skeleton'] . '.twig');
        }
        $content = array_merge($content, ['debugbarHead' => $debugbarHead, 'debugbarFoot' => $debugbarFoot, 'skeleton' => $skeleton]);
        if ($extKey === 'admin') {
            if ($service['extAction'] === 'login') $content = array_merge($content, ['formToken' => $service['formToken']]);
            $renderedPage = $twigTemplate->render($service['extAction'] . '.twig', $content);
        } else {
            $renderedPage = $twigTemplate->render($extKey . '.twig', $content);
        }
        return $renderedPage;
    }
}