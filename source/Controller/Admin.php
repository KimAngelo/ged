<?php


namespace Source\Controller;


use Source\Core\Controller;

/**
 * Class Admin
 * @package Source\Controller
 */
class Admin extends Controller
{
    /**
     * Admin constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
    }
}