<?php
namespace Zorca;

class Autoload {
    static function load($autoloadParam) {
        $autoloadConfig = Config::load($autoloadParam);
        foreach ($autoloadConfig as $autoloadConfigItem) {
            $autoloadConfigItemKey = $autoloadParam . 'Key';
            $autoloadClassFile = BASE . $autoloadParam . DS . $autoloadConfigItem[$autoloadConfigItemKey] . DS . $autoloadConfigItem[$autoloadConfigItemKey] . '.php';
            if (file_exists($autoloadClassFile)) require_once($autoloadClassFile);
        }
    }
}