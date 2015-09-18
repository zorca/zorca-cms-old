<?php
namespace Zorca;

use Zorca\Config;
class Auth {

    public function isAuth() {
        if (isset($_SESSION["is_auth"])) {
            return $_SESSION["is_auth"];
        }
        else return false;
    }

    static function check($login, $password) {
        $cred = Config::load('admin');
        if ($login === $cred['login'] && $password === $cred['password']) {
            $_SESSION["is_auth"] = true;
            $_SESSION["login"] = $login;
            return true;
        }
        else {
            $_SESSION["is_auth"] = false;
            return false;
        }
    }
}