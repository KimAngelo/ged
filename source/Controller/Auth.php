<?php


namespace Source\Controller;


use Source\Core\Controller;

/**
 * Class Auth
 * @package Source\Controller
 */
class Auth extends Controller
{
    /**
     * Auth constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
    }
}