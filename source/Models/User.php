<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;
use Source\Core\Session;

class User extends DataLayer
{
    public function __construct()
    {
        parent::__construct('users', ['first_name', 'last_name', 'email', 'password', 'admin', 'roles', 'companies']);
    }

    public static function user()
    {
        $session = new Session();
        if (!$session->has('authUser')) {
            return null;
        }
        return (new User())->findById($session->authUser);
    }

}