<?php
namespace Zorca\Ext;

use Symfony\Component\HttpFoundation\Response;
use ParsedownExtra;
use Zorca\Theme;
use Zorca\SCSS;
class PagesExt {
    public function run($extAction) {
        $responseStatus = '200';
        $parsedown = new ParsedownExtra();
        $pageContentFilePath = BASE . 'data/pages' . DS . $extAction . '.md';
        if (!file_exists($pageContentFilePath)) {
            $pageContentFilePath = BASE . 'data/pages/404.md';
            $responseStatus = '404';
        }
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $scss = new Scss();
        $scss->compileFile([
            BASE. 'palettes/default.scss',
            BASE. 'themes/default/styles/_config.scss',
            BASE. 'app/oxi/_variables.scss',
            BASE. 'app/oxi/_clearfix.scss',
            BASE. 'app/oxi/_hover.scss',
            BASE. 'app/oxi/_tab-focus.scss',
            BASE. 'app/oxi/_grid.scss',
            BASE. 'app/oxi/_normalize.scss',
            BASE. 'app/oxi/_print.scss',
            BASE. 'app/oxi/_reboot.scss',
            BASE. 'skeletons/default/default.scss',
            BASE. 'themes/default/styles/_01-base.scss',
            BASE. 'themes/default/styles/main.scss'],
            BASE. 'pub/styles/main.css');
        $theme = new Theme();
        $renderedPage = $theme->render($pageContent);
        $response = new Response($renderedPage, $responseStatus);
        return $response;
    }
}