<?php
namespace Zorca\Ext;

use Zorca\Config;
/**
 * Class MenuMod
 * @package Zorca\Ext
 */
class MenuMod {
    /**
     * Универсальный модуль меню MenuMod
     *
     * @param $extKey
     * @param $menuName
     * @param $mod
     *
     * @return string
     */
    static function load($extKey, $menuName, $mod) {
        $config = Config::load('ext');
        foreach ($config as $configItem) {
            if ($configItem['extKey'] === $extKey) {
                $extType = $configItem['extType'];
                $extSlug = $configItem['extSlug'];
            }
        }
        if (!isset($extType)) $extType = 'component';
        if (!isset($extSlug) || $extSlug === '/') $extSlug = '';
        $beforeMenu ='';
        $loadMenu = '';
        $afterMenu = '';
        $menuFilePath = DATA . 'ext' . DS . $extType . 's' . DS . $extKey . DS . 'menu' . DS . $menuName . '.json';
        if (file_exists($menuFilePath)) $menu = json_decode(file_get_contents($menuFilePath), true); else $menu = [];
        foreach($menu as $menuItem) {
            if ($menuItem['menuLink'] === '/') $menuItem['menuLink'] = '';
            if ($mod) {
                $beforeMenu = '<ul class="m-menu m-menu--' . $mod . '">';
                $loadMenu = $loadMenu . '<li class="m-menu__item m-menu__item--' . $mod.'"><a class="m-menu__link m-menu__link--' . $mod . '" href="' . $extSlug . DS . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
                $afterMenu = '</ul>';
            } else {
                $beforeMenu = '<ul class="m-menu">';
                $loadMenu = $loadMenu . '<li class="m-menu__item"><a class="m-menu__link" href="' . $menuItem['menuLink'] . '">' . $menuItem['menuItem'] . '</a></li>';
                $afterMenu = '</ul>';
            }
        }
        $loadMenu = $beforeMenu . $loadMenu . $afterMenu;
        return $loadMenu;
    }
}