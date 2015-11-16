<?php
    namespace Zorca;

    use Leafo\ScssPhp\Compiler;

    class Scss
    {

        private $importPath = [];
        private $scss = '';

        public function compile($scss) {
            $compiler = new Compiler();
            $compiler->setImportPaths($this->importPath);
            return $compiler->compile($scss);
        }

        public function compileFile($in, $out) {
            $this->resetScss();

            foreach ($in as $inItem) {
                $this->importPath($inItem);
            }

            $css = $this->compile($this->scss);
            $css = csscrush_string($css, ['formatter' => 'block']);
            if (!file_exists($out)) {
                if (!file_exists(dirname($out))) {
                    mkdir(dirname($out), 0775, true);
                }
            }
            return file_put_contents($out, $css);
        }

        public function resetScss() {
            $this->scss = '@charset "utf-8";';
        }

        public function setScss($scss) {
            $this->scss = $scss;
        }

        public function appendScss($scss) {
            $this->scss .= PHP_EOL . $scss;
        }

        private function importPath($scssFile) {
            $scss = '';
            $path = pathinfo($scssFile);
            if (file_exists($pathRelateImport = $scssFile) ||
                file_exists($pathRelateImport = $scssFile . (!isset($path['extension']) ? '.scss' : '')) ||
                file_exists($pathRelateImport = dirname($scssFile) . '/_' . basename($scssFile) . (!isset($path['extension']) ? '.scss' : ''))
            ) {
                $scss = file_get_contents($pathRelateImport);
            }
            if (!empty($scss)) {
                $this->appendScss($scss);
                preg_match_all('%(//)?.*@import\s*"(.*)"%i', $scss, $result, PREG_PATTERN_ORDER);
                for ($i = 0; $i < count($result[0]); $i++) {
                    if (empty($result[1][$i])) {
                        $pathImport = dirname($scssFile) . DS . $result[2][$i];
                        if (!in_array(dirname($scssFile), $this->importPath)) {
                            $this->importPath[] = dirname($scssFile);
                        }
                        $this->importPath($pathImport);
                    }
                }
            }
        }
    }