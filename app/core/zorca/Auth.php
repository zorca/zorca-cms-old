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
    static function generateFormToken() {
        $token = md5(uniqid(microtime(), true));
        $_SESSION['_token'] = $token;
        return $token;
    }
    /**
     * Проверка секретного токена для формы
     *
     * @return string
     */
    static function verifyFormToken() {
        if(!isset($_SESSION['_token'])) {
            return false;
        }
        if(!isset($_POST['token'])) {
            return false;
        }
        if ($_SESSION['_token'] !== $_POST['token']) {
            return false;
        }
        return true;
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
    static function in($login, $password) {
        $cred = Config::load('admin');
        if ($login === $cred['login'] && password_verify($password, $cred['password'])) {
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