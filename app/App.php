<?php

namespace Rubu\Parduotuve;

use Rubu\Parduotuve\Controllers\RubuController;
use Rubu\Parduotuve\Controllers\LoginController;
use PDO;
use Rubu\Parduotuve\Messages;

class App
{


    static $pdo;

    public static function start()
    {
        self::db();
        return self::route();
    }

    public static function route()
    {
        $userUri = $_SERVER['REQUEST_URI'];
        $userUri = str_replace(INSTALL_DIR, '', $userUri);
        $userUri = preg_replace('/\?.*$/', '', $userUri);
        $userUri = explode('/', $userUri);

        if (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'sarasas' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new RubuController)->list();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'test' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new RubuController)->selectTest();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'login' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->doLogin();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'login' == $userUri[0] &&
            count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('edit');
            }
            return (new LoginController)->show();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'pirkti' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new RubuController)->buy();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'register' == $userUri[0] &&
            count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('edit');
            }
            return (new LoginController)->register();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'register' == $userUri[0] &&
            count($userUri) == 1
        ) {
            if (LoginController::isLogged()) {
                self::redirect('edit');
            }
            return (new LoginController)->doRegister();
        }


        if (!LoginController::isLogged()) {
            self::redirect('login');
        }


        if (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'tags' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new RubuController)->showTags();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'GET' &&
            'edit' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new RubuController)->edit();
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'update' == $userUri[0] &&
            count($userUri) == 2
        ) {
            return (new RubuController)->update($userUri[1]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'add-size' == $userUri[0] &&
            count($userUri) == 2
        ) {
            return (new RubuController)->addSize($userUri[1]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'remove-tag' == $userUri[0] &&
            count($userUri) == 2
        ) {
            return (new RubuController)->removeTag($userUri[1]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'add-tag' == $userUri[0] &&
            count($userUri) == 2
        ) {
            return (new RubuController)->addTag($userUri[1]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'tags' == $userUri[0] && 'update' == $userUri[1] &&
            count($userUri) == 3
        ) {
            return (new RubuController)->updateTag($userUri[2]);
        } elseif (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            'logout' == $userUri[0] &&
            count($userUri) == 1
        ) {
            return (new LoginController)->doLogOut();
        }

        echo '<h1>404 Page Not Found</h1>';
    }

    public static function redirect($where)
    {
        header('Location: ' . URL . $where);
        die;
    }

    public static function db()
    {
        $host = getSetting('host');
        $db   = getSetting('db');
        $user = getSetting('user');
        $pass = getSetting('pass');
        $charset = 'utf8mb4';
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        self::$pdo = new PDO($dsn, $user, $pass, $options);
    }


    public static function view($temp, $data = [])
    {
        extract($data);
        $appUser = $_SESSION['name'] ?? '';
        $messages = Messages::get();
        require DIR . 'views/' . $temp . '.php';
    }
}
