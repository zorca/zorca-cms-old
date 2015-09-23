<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zorca\Config;
use Zorca\Scss;
use Zorca\Auth;
use Zorca\Theme;
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
        $renderedPage = $renderedPage = Theme::render([
            'menuMainContent' => $menuMainContent,
            'menuSidebarContent' => $menuSidebarContent,
            'adminContent' => $adminContent],
            ['extAction' => $extAction, 'formToken' => $formToken], 'admin');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}