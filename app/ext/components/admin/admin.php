<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Zorca\Auth;
use Zorca\Template;

/**
 * Class AdminExt
 *
 * @package Zorca\Ext
 */
class AdminExt {
    /**
     * @param $extRequest
     * @param $extAction
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
        $renderedPage = $renderedPage = Template::render([
            'menuMainContent' => $menuMainContent,
            'menuSidebarContent' => $menuSidebarContent,
            'adminContent' => $adminContent],
            ['extAction' => $extAction, 'formToken' => $formToken], 'admin');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}