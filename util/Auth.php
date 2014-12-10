<?php
class Auth
{
    public static function handleLogin()
    {
        $logged = $_SESSION['loggedIn'];
        if ($logged == false) {
            Session::destroy();
            header('location: ../admin/login');
        }
        exit;
    }

    public static function logout()
    {
        Session::destroy();
        header('location: '.URL);
    }
}