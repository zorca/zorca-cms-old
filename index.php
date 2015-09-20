<?php
/**
 * @todo Сделать режимы запуска: development, production
 */
// Проверяем версию PHP
if (version_compare($ver = PHP_VERSION, $req = '5.4.0', '<')) {
    throw new \RuntimeException(sprintf('You are running PHP %s, but Zorca needs at least <strong>PHP %s</strong> to run.', $ver, $req));
}

// Устанавливаем временную зону по-умолчанию, на случай, если она не установлена в php.ini
date_default_timezone_set(@date_default_timezone_get());

// Проверяем установлено ли расширение mbstring
if (!extension_loaded('mbstring')) {
    throw new \RuntimeException("'mbstring' extension is not loaded.  This is required for Zorca to run correctly");
}
mb_internal_encoding('UTF-8');

// Определяем базовую папку сервера
define('DS', '/');
define('BASE', str_replace(DIRECTORY_SEPARATOR, DS, __DIR__ . DS));
define('APP', BASE . 'app' . DS);
define('DATA', BASE . 'data' . DS);
define('PUB', BASE . 'pub' . DS);

// Проверяем, установлены ли необходимые библиотеки
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
    throw new \RuntimeException('Please run: <i>composer install</i>');
}

// Подгружаем библиотеки
require_once $autoload;

// Запускаем движок
$zorca = new Zorca\Zorca();