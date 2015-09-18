<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use Zorca\Theme;
use Zorca\Scss;
use Zorca\Auth;
class AdminExt {
    public function run($extRequest, $extAction) {
        session_start();
        $auth = new Auth();
        if ($auth->is()) {
            echo 'Здравствуйте';
            echo '<br/><br/><a href="">Выйти</a>';
        }
        $responseStatus = '200';
        if ($extRequest->request->get('login')) {
            $login = $extRequest->request->get('login');
            $password = $extRequest->request->get('password');
            Auth::in($login, $password);
        } else {
            Auth::out();
        }
        $menu = new Menu();
        $menuContent = $menu->load('menuMain');
        $scss = new Scss();
        $scss->compileFile([
            BASE. 'app/design/palettes/default.scss',
            BASE. 'app/design/themes/default/styles/_config.scss',
            BASE. 'app/oxi/_functions.scss',
            BASE. 'app/oxi/_variables.scss',
            BASE. 'app/oxi/_clearfix.scss',
            BASE. 'app/oxi/_hover.scss',
            BASE. 'app/oxi/_tab-focus.scss',
            BASE. 'app/oxi/_grid.scss',
            BASE. 'app/oxi/_normalize.scss',
            BASE. 'app/oxi/_print.scss',
            BASE. 'app/oxi/_reboot.scss',
            BASE. 'app/design/skeletons/default/default.scss',
            BASE. 'app/design/themes/default/styles/_01-base.scss',
            BASE. 'app/design/themes/default/styles/main.scss'],
            BASE. 'pub/styles/main.css');
        $theme = new Theme();
        $pageContentFile = APP . 'ext/components/admin/views' . DS . 'index.php';
        if (file_exists($pageContentFile)) $pageContent = file_get_contents($pageContentFile); else $pageContent = '';
        $renderedPage = $theme->render($menuContent, $pageContent, 'admin');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}