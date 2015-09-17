<?php
namespace Zorca;

use Leafo\ScssPhp\Compiler;
class Scss {
    public function compile($scss) {
        $compiler = new Compiler();
        return $compiler->compile($scss);
    }
    public function compileFile($in, $out) {
        $scss = '@charset "utf-8";';
        foreach($in as $inItem) {
            if (file_exists($inItem)) $scss = $scss . PHP_EOL . file_get_contents($inItem);
        }
        $css = $this->compile($scss);
        return file_put_contents($out, $css);
    }
}