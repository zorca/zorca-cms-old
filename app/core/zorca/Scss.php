<?php
namespace Zorca;

use Leafo\ScssPhp\Compiler;
/**
 * Class Scss
 * @package Zorca
 */
class Scss {
    /**
     * Массив путей для импорта
     * @var array
     */
    private $importPaths = [];

    /**
     * Функция подключения путей для импорта
     * @param $importPaths
     */
    public function setImportPaths($importPaths) {
        $this->importPaths = $importPaths;
    }

    /**
     * Функция компиляции compile: текст scss -> текст css
     * @param $scss
     * @return string
     */
    public function compile($scss) {
        $compiler = new Compiler();
        $compiler->setImportPaths($this->importPaths);
        return $compiler->compile($scss);
    }

    /**
     * Функция компиляции compileFile: файл scss -> файл css
     * Возвращает TRUE, если запись в файл прошла удачно
     * @param $in
     * @param $out
     * @return boolean
     */
    public function compileFile($in, $out) {
        $scss = '@charset "utf-8";';
        foreach($in as $inItem) {
            if (file_exists($inItem)) $scss = $scss . PHP_EOL . file_get_contents($inItem);
        }
        $css = $this->compile($scss);
        if (!file_exists($out)) mkdir(dirname($out), 0775, true);
        return (boolean) file_put_contents($out, $css);
    }
}