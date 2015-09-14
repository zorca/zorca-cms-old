<?php
namespace Zorca\Ext;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Error;
use ParsedownExtra;
class PagesExt {
    public function run($extAction) {
        $parsedown = new ParsedownExtra();
        $templates = new Twig_Loader_Filesystem(BASE . 'pub/themes/default/templates');
        $twig = new Twig_Environment($templates);
        $pageContentFilePath = BASE .  'data/pages' . DS . $extAction . '.md';
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        return $twig->render('pages.twig', ['pageContent' => $pageContent]);
    }
}