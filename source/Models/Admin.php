<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;
use Source\Core\Session;
use Source\Support\Message;

/**
 * Modelo que controla os usuários com acesso administrativo
 * Class Admin
 * @package Source\Models
 */
class Admin extends DataLayer
{
    /**
     * @var Message
     */
    private $message;

    /**
     * Admin constructor.
     */
    public function __construct()
    {
        parent::__construct('admin', ['first_name', 'email', 'password', 'level']);
        $this->message = new Message();
    }

    /**
     * @return DataLayer|null
     */
    public static function userAdmin(): ?DataLayer
    {
        $session = new Session();
        if (!$session->has("adminUser")) {
            return null;
        }
        return (new Admin())->findById($session->adminUser);
    }



    /**
     * log-out
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("adminUser");
    }
}