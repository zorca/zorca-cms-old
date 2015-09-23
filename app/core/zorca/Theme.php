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
     * @param $content
     * @param $service
     * @param $extKey
     *
     * @return string
     */
    static function render($content, $service, $extKey) {
        $config = Config::load('ext');
        $extType = 'component';
        foreach ($config as $configItem) {
            if ($configItem['extKey'] === $extKey) {
                $extType = $configItem['extType'];
            }

        }
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
        $renderedPage = $twigTemplate->render($extKey . '.twig', $content);
        return $renderedPage;
    }
}