<?php
    namespace Zorca;

    use Leafo\ScssPhp\Compiler;

    class Scss
    {

        private $importPath = [];

        public function compile($scss) {
            $compiler = new Compiler();
            foreach ($this->importPath as $path) {
                $compiler->addImportPath($path);
            }
            return $compiler->compile($scss);
        }

        public function compileFile($in, $out) {
            $scss = '@charset "utf-8";';
            foreach ($in as $inItem) {
                if (file_exists($inItem)) {
                    $scss = $scss . PHP_EOL . file_get_contents($inItem);
                    $this->importPath($inItem);
                }
            }

            $css = $this->compile($scss);
            $css = csscrush_string($css, ['formatter' => 'block']);
            if (!file_exists($out)) {
                if (!file_exists(dirname($out))) {
                    mkdir(dirname($out), 0775, true);
                }
            }l
            return file_put_contents($out, $css);
        }

        private function importPath($scssFile) {
            if (file_exists($scssFile)) $scss = file_get_contents($scssFile);
            preg_match_all('%(//)?.*@import\s*"(.*)"%i', $scss, $result, PREG_PATTERN_ORDER);
            for ($i = 0; $i < count($result[0]); $i++) {
                if (empty($result[1][$i])) {
                    $path = dirname($scssFile) . DS . $result[2][$i];
                    if (!in_array(dirname($scssFile), $this->importPath)) {
                        $this->importPath[] = dirname($scssFile);
                    }
                    if (file_exists($pathRelateImport = $path) || file_exists($pathRelateImport = $path . '.scss') || file_exists($pathRelateImport = dirname($path) . '/_' . basename($path) . '.scss')) {
                        $this->importPath($pathRelateImport);
                    }
                }
            }
        }
    }