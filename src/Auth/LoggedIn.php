<?php
namespace Acme\Auth;

/**
 * Class LoggedIn
 * @package Acme\Auth
 */
class LoggedIn
{

    /**
     * @return bool|Acme\Models\User
     */
    public static function user()
    {
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            return $user;
        } else {
            return false;
        }
    }
}
