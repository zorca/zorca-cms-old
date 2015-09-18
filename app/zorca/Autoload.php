<?php
namespace Zorca;

class Autoload {
    static function load() {
        $autoloadConfig = Config::load('ext');
        foreach ($autoloadConfig as $autoloadConfigItem) {
            $autoloadConfigItemKey = 'extKey';
            $autoloadClassFile = APP . 'ext' . DS . $autoloadConfigItem['extType'] . 's' . DS . $autoloadConfigItem[$autoloadConfigItemKey] . DS . $autoloadConfigItem[$autoloadConfigItemKey] . '.php';
            if (file_exists($autoloadClassFile)) require_once($autoloadClassFile);
        }
    }
}