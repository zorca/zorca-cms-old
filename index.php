<?php
// Проверяем версию PHP
if (version_compare($ver = PHP_VERSION, $req = '5.4.0', '<')) {
    throw new \RuntimeException(sprintf('Вы используете PHP %s, но системе Zorca CMS для запуска требуется PHP %s.', $ver, $req));
}

// Проверяем, установлены ли необходимые библиотеки
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    throw new \RuntimeException('Не установлены внешние библиотеки. Запустите команду composer install');
}

// Подгружаем библиотеки
require_once $autoload;

$zorca = new Zorca\Zorca();