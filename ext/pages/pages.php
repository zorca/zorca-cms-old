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
        $skeletons = new Twig_Loader_Filesystem(BASE . 'pub/skeletons/default/templates');
        $twig = new Twig_Environment($templates);
        $twigSkeleton = new Twig_Environment($skeletons);
        $pageContentFilePath = BASE .  'data/pages' . DS . $extAction . '.md';
        $pageContent = $parsedown->text(file_get_contents($pageContentFilePath));
        $skeleton = $twigSkeleton->loadTemplate('default.twig');
        return $twig->render('pages.twig', ['pageContent' => $pageContent, 'skeleton' => $skeleton]);
    }
}