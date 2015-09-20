<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use ParsedownExtra;
use Zorca\Theme;
use Zorca\Scss;
class PagesExt {
    public function run($extRequest, $extAction) {
        $responseStatus = '200';
        $parsedown = new ParsedownExtra();
        $pageContentFilePath = DATA . 'ext/components/pages' . DS . $extAction . '.md';
        if (!file_exists($pageContentFilePath)) {
            $pageContentFilePath = DATA . 'ext/components/pages/404.md';
            $responseStatus = '404';
        }
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $menu = new Menu();
        $menuContent = $menu->load('menuMain');
        /** @todo Требуется переделать ввод через массив стилей, поданный в класс
         *  @todo Также решить вопрос с import внутри стилей scss
         **/
        $scss = new Scss();
        $scss->compileFile([
            BASE. 'app/design/palettes/default.scss',
            BASE. 'app/design/themes/default/styles/_config.scss',
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
            BASE. 'app/design/themes/default/styles/_01-base.scss',
            BASE. 'app/design/themes/default/styles/main.scss'],
            BASE. 'pub/styles/main.css');
        $theme = new Theme();
        $renderedPage = $theme->render($menuContent, $pageContent, 'pages');
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}