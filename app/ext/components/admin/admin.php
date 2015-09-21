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
        $responseStatus = '200';
        if ($extRequest->request->get('token')) {
            $login = $extRequest->request->get('login');
            $password = $extRequest->request->get('password');
            Auth::in($login, $password);
        } else {

        }
        if ($extAction === 'logout') { Auth::out(); return new RedirectResponse('/'); }
        $scss = new Scss();
        $scss->compileFile([
            BASE. 'app/ext/components/admin/theme/styles/_config.scss',
            BASE. 'app/core/oxi/_functions.scss',
            BASE. 'app/core/oxi/_variables.scss',
            BASE. 'app/core/oxi/_clearfix.scss',
            BASE. 'app/core/oxi/_hover.scss',
            BASE. 'app/core/oxi/_tab-focus.scss',
            BASE. 'app/core/oxi/_grid.scss',
            BASE. 'app/core/oxi/_normalize.scss',
            BASE. 'app/core/oxi/_print.scss',
            BASE. 'app/core/oxi/_reboot.scss',
            BASE. 'app/design/skeletons/default/default.scss',
            BASE. 'app/ext/components/admin/theme/styles/main.scss'],
            BASE. 'pub/styles/admin.css');
        $menuContent = '';
        $adminContent = '';
        if (Auth::is()) {
            $menuContent = $this->menu();
            $adminContent = '';
        }
        $renderedPage = $this->theme($menuContent, $adminContent, $extAction);
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }

    /**
     * @return string
     */
    private function menu() {
        $config = Config::load('ext');
        $adminSlug = '/admin';
        foreach ($config as $configItem) {
            if ($configItem['extKey'] === 'admin') $adminSlug = $configItem['extSlug'];
        }
        $beforeMenu = '<ul class="m-menu">';
        $loadMenu = '';
        $afterMenu = '<li class="m-menu__item"><a class="m-menu__link" href="' . $adminSlug . DS . 'logout' . '">' . 'Выйти' . '</a></li></ul>';
        $menuFilePath = APP . 'ext/components/admin/menu/menu.json';
        // Если файл конфига не существует, то отдаем пустой массив
        if (file_exists($menuFilePath)) $menu = json_decode(file_get_contents($menuFilePath), true); else $menu = [];
        foreach($menu as $menuItem) {
            $loadMenu = $loadMenu . '<li class="m-menu__item"><a class="m-menu__link" href="' . $adminSlug . DS . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
        }
        $loadMenu = $beforeMenu . $loadMenu . $afterMenu;
        return $loadMenu;
    }

    /**
     *
     */
    private function theme($menuContent, $adminContent, $extAction) {
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
        $renderedPage = $twigTemplate->render($extAction . '.twig', ['debugbarHead' => $debugbarHead, 'debugbarFoot' => $debugbarFoot, 'menuContent' => $menuContent, 'adminContent' => $adminContent, 'skeleton' => $skeleton]);
        return $renderedPage;
    }
}