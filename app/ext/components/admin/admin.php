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
            $menuMainContent = MenuMod::load('admin', 'menuMain', 'horizontal');
            $menuSidebarContent =  MenuMod::load('admin', 'menuSidebar', 'vertical');
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
     * Тема административной панели
     *
     * @param $content
     * @param $service
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