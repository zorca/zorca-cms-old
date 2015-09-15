<?php
namespace Zorca;

class Config {
    static function load($configParam) {
        // Если не указано, какой конфиг загрузить, загружаем основной
        if (!is_null($configParam)) {
            $configFilePath = BASE . $configParam . DS . $configParam . '.json';
        } else {
            $configFilePath = BASE . 'data/config.json';
        }
        // Если файл конфига не существует, то отдаем пустой массив
        if (file_exists($configFilePath)) {
            $config = json_decode(file_get_contents($configFilePath), true);
        } else {
            $config = [];
        }
        return $config;
    }
}