<?php
namespace Rubu\Parduotuve\Controllers;

use Rubu\Parduotuve\App;
use Rubu\Parduotuve\Messages;


class LoginController {

    static private function logIn()
    {
        $name = $_POST['user'];
        $pass = md5($_POST['pass']);
        $sql = "SELECT 
        *
        FROM
        users
        WHERE user = '$name' AND pass = '$pass'
        ";
        $stmt = App::$pdo->query($sql);
        $user = $stmt->fetch();

        if (false === $user) {
            Messages::add('danger', 'Blogas slaptažodis arba vardas');
            return false;
        }

        $_SESSION['name'] = $user['user'];
        $_SESSION['logged'] = 1;
        Messages::add('success', 'Vartotojas '. $user['user'] . 'sėkmingai pajungas.');
        return true;
    }

    static public function isLogged()
    {
        return isset($_SESSION['logged']) && $_SESSION['logged'] == 1;
    }
    
    
    public function show()
    {
        App::view('login');
    }

    public function register()
    {
        App::view('register');
    }

    public function doRegister()
    {
        $name = $_POST['user'];
        $pass = md5($_POST['pass']);
        $sql = "INSERT INTO
        users
        (user, pass)
        VALUES ('$name', '$pass')
        ";
        App::$pdo->query($sql);
        Messages::add('success', 'Vartotojas '. $name . ' sėkmingai užregistruotas.');
        App::redirect('login');
    }

    public function doLogin()
    {
        $ok = self::logIn();

        if (!$ok) {
            App::redirect('login');
        }
        else {
            App::redirect('edit');
        }
    }

    public function doLogOut()
    {
        unset($_SESSION['name'], $_SESSION['logged']);
        App::redirect('login');
    }
    
}