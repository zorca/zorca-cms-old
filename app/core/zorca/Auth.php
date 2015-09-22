<?php
namespace Zorca;

use Zorca\Config;
/**
 * Авторизация администратора
 *
 * Class Auth
 * @package Zorca
 */
class Auth {
    /**
     * Генерация секретного токена для формы
     *
     * @return string
     */
    static function formToken() {
        $token = md5(uniqid(microtime(), true));
        return $token;
    }
    /**
     * Проверка, авторизован ли администратор
     *
     * @return bool
     */
    static function is() {
        if (isset($_SESSION["is_auth"])) {
            return $_SESSION["is_auth"];
        }
        else return false;
    }

    /**
     * Вход в админку
     *
     * @param $login
     * @param $password
     * @return bool
     */
    static function in($login, $password, $formToken) {
        $cred = Config::load('admin');
        if ($login === $cred['login'] && password_verify($password, $cred['password']) && $_SESSION['_token'] === $formToken) {
            $_SESSION["is_auth"] = true;
            $_SESSION["login"] = $login;
            return true;
        }
        else {
            $_SESSION["is_auth"] = false;
            return false;
        }
    }

    /**
     * Выход из админки
     *
     * Удаляет сессию
     */
    static function out() {
        $_SESSION = [];
        session_destroy();
    }
}