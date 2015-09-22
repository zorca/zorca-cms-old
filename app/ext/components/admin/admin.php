<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zorca\Config;
use Zorca\Theme;
use Zorca\Scss;
use Zorca\Auth;
use Twig_Loader_Filesystem;
use Twig_Environment;
use DebugBar\StandardDebugBar;
/**
 * Class AdminExt
 * @package Zorca\Ext
 */
class AdminExt {
    /**
     * @param $extRequest
     * @param $extAction
     * @return Response
     */
    public function run($extRequest, $extAction) {

        session_start();
        $formToken = Auth::formToken();
        $_SESSION['_token'] = $formToken;
        $responseStatus = '200';
        if ($extRequest->request->get('token')) {
            $login = $extRequest->request->get('login');
            $password = $extRequest->request->get('password');
            Auth::in($login, $password, $formToken);
        }
        if ($extAction === 'logout') { Auth::out(); return new RedirectResponse('/'); }
        $scss = new Scss();
        $scss->setImportPaths([ BASE . 'app/core/oxi',
                                BASE . 'app/design/skeletons',
                                BASE . 'app/ext/components/admin/themes/default/styles']);
        $scss->compileFile([    BASE. 'app/design/themes/default/styles/main.scss'],
                                BASE. 'pub/styles/admin.css');
        $menuContent = '';
        $adminContent = '';
        if (Auth::is()) {
            $menuContent = $this->menu('menuMain');
            $adminContent = '';
        } else {
            $extAction = 'login';
        }
        $renderedPage = $this->theme($menuContent, $adminContent, $extAction, $formToken);
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }

    /**
     * Меню панели администратора
     *
     * @return string
     */
    private function menu($menuName) {
        $config = Config::load('ext');
        $adminSlug = '/admin';
        foreach ($config as $configItem) {
            if ($configItem['extKey'] === 'admin') $adminSlug = $configItem['extSlug'];
        }
        $beforeMenu = '<ul class="m-menu">';
        $loadMenu = '';
        $afterMenu = '<li class="m-menu__item"><a class="m-menu__link" href="' . $adminSlug . DS . 'logout' . '">' . 'Выйти' . '</a></li></ul>';
        $menuFilePath = APP . 'ext/components/admin/menu/' . $menuName . '.json';
        // Если файл конфига не существует, то отдаем пустой массив
        if (file_exists($menuFilePath)) $menu = json_decode(file_get_contents($menuFilePath), true); else $menu = [];
        foreach($menu as $menuItem) {
            $loadMenu = $loadMenu . '<li class="m-menu__item"><a class="m-menu__link" href="' . $adminSlug . DS . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
        }
        $loadMenu = $beforeMenu . $loadMenu . $afterMenu;
        return $loadMenu;
    }

    /**
     * Тема административной панели
     *
     * @param $menuContent
     * @param $adminContent
     * @param $extAction
     *
     * @return string
     */
    private function theme($menuContent, $adminContent, $extAction, $formToken) {
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
        $templates = new Twig_Loader_Filesystem(APP . 'ext/components/admin/pages');
        $skeletons = new Twig_Loader_Filesystem(APP . 'ext/components/admin/skeletons/default');
        $twigTemplate = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $skeleton = $twigSkeleton->loadTemplate('default.twig');
        if (!$extAction) $extAction = 'index';

        $renderedPage = $twigTemplate->render($extAction . '.twig',
                [   'debugbarHead' => $debugbarHead,
                    'debugbarFoot' => $debugbarFoot,
                    'menuContent' => $menuContent,
                    'adminContent' => $adminContent,
                    'skeleton' => $skeleton,
                    'formToken' => $formToken
                ]);
        return $renderedPage;
    }
}