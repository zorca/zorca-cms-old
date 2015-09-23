<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zorca\Config;
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
     *
     * @return Response
     */
    public function run($extRequest, $extAction) {
        session_start();
        if ($extAction === 'logout') { Auth::out(); return new RedirectResponse('index'); }
        if (Auth::verifyFormToken()) {
            $login = $extRequest->request->get('login');
            $password = $extRequest->request->get('password');
            Auth::in($login, $password);
        }
        $responseStatus = '200';
        $scss = new Scss();
        $scss->setImportPaths([ BASE . 'app/core/oxi',
                                BASE . 'app/ext/components/admin/design/skeletons',
                                BASE . 'app/ext/components/admin/design/themes/default/styles']);
        $scss->compileFile([    BASE. 'app/ext/components/admin/design/themes/default/styles/main.scss'],
                                BASE. 'pub/styles/admin.css');
        $menuMainContent = '';
        $menuSidebarContent = '';
        $adminContent = '';
        if (Auth::is()) {
            $menuMainContent = $this->menu('menuMain', '--horizontal');
            $menuSidebarContent = $this->menu('menuSidebar', '--vertical');
            $adminContent = '';
            $formToken = '';
        } else {
            $extAction = 'login';
            $formToken = Auth::generateFormToken();
        }
        $renderedPage = $this->theme([  'menuMainContent' => $menuMainContent,
                                        'menuSidebarContent' => $menuSidebarContent,
                                        'adminContent' => $adminContent],
                                        ['extAction' => $extAction, 'formToken' => $formToken]
                                        );
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }

    /**
     * Меню панели администратора
     *
     * @return string
     */
    private function menu($menuName, $mod) {
        $config = Config::load('ext');
        $adminSlug = '/admin';
        foreach ($config as $configItem) {
            if ($configItem['extKey'] === 'admin') $adminSlug = $configItem['extSlug'];
        }
        $beforeMenu = '<ul class="m-menu">';
        $loadMenu = '';
        $afterMenu = '</ul>';
        $menuFilePath = APP . 'ext/components/admin/menu/' . $menuName . '.json';
        // Если файл конфига не существует, то отдаем пустой массив
        if (file_exists($menuFilePath)) $menu = json_decode(file_get_contents($menuFilePath), true); else $menu = [];
        foreach($menu as $menuItem) {
            $loadMenu = $loadMenu . '<li class="m-menu__item m-menu__item' . $mod . '"><a class="m-menu__link" href="' . $adminSlug . DS . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
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
    private function theme($content, $service) {
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
        $skeletons = new Twig_Loader_Filesystem(APP . 'ext/components/admin/design/skeletons/default');
        $twigTemplate = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $skeleton = $twigSkeleton->loadTemplate('default.twig');
        if (!$service['extAction']) $service['extAction'] = 'index';
        $renderedPage = $twigTemplate->render($service['extAction'] . '.twig',
                [   'debugbarHead' => $debugbarHead,
                    'debugbarFoot' => $debugbarFoot,
                    'menuMainContent' => $content['menuMainContent'],
                    'menuSidebarContent' => $content['menuSidebarContent'],
                    'adminContent' => $content['adminContent'],
                    'skeleton' => $skeleton,
                    'formToken' => $service['formToken']
                ]);
        return $renderedPage;
    }
}