<?php
namespace Zorca;

class Zorca {
    private $mainConfig;
    private $extConfig;
    public function __construct() {
        //var_dump($this->loadMainConfig());
        var_dump($this->loadExtConfig());
    }
    private function loadMainConfig() {
        $mainConfigFilePath = BASE . 'app/config.json';
        $mainConfigDefault = ["skeleton" => "default", "theme" =>"default"];
        if (file_exists($mainConfigFilePath)) {
            $this->mainConfig = json_decode(file_get_contents($mainConfigFilePath), true);
        } else {
            $this->mainConfig = $mainConfigDefault;
        }
        return $this->mainConfig;
    }
    private function loadExtConfig() {
        $extConfigFilePath = BASE . 'ext/ext.json';
        $extConfigDefault = [];
        if (file_exists($extConfigFilePath)) {
            $this->extConfig = json_decode(file_get_contents($extConfigFilePath));
            if ($this->extConfig == NULL) $this->extConfig = $extConfigDefault;
        } else {
            $this->extConfig = $extConfigDefault;
        }
        return $this->extConfig;
    }
}