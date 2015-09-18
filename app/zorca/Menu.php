<?php
namespace Zorca;

class Menu {
    public function load($menuName) {
        $beforeMenu ='';
        $loadMenu = '';
        $afterMenu = '';
        $menuFilePath = DATA . 'ext/modules/menu' . DS . $menuName . '.json';
        // Если файл конфига не существует, то отдаем пустой массив
        if (file_exists($menuFilePath)) $menu = json_decode(file_get_contents($menuFilePath), true); else $menu = [];
        foreach($menu as $menuItem) {
            $beforeMenu = '<ul class="m-menu">';
            $loadMenu = $loadMenu . '<li class="m-menu__item"><a class="m-menu__link" href="' . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
            $afterMenu = '</ul>';
        }
        $loadMenu = $beforeMenu . $loadMenu . $afterMenu;
        return $loadMenu;
    }
}