<?php


namespace Source\Core;


use CoffeeCode\Router\Router;
use Source\Support\Message;
use Source\Support\Seo;

/**
 * Class Controller
 * @package Source\Core
 */
class Controller
{
    /**
     * @var View
     */
    protected $view;

    /**
     * @var Seo
     */
    protected $seo;

    /**
     * @var Message
     */
    protected $message;

    /** @var Router */
    protected $router;

    /**
     * Controller constructor.
     * @param null $pathToViews
     */
    public function __construct($router, $pathToViews = null)
    {
        $this->view = new View($router, $pathToViews);
        $this->seo = new Seo();
        $this->message = new Message();
        $this->router = $router;
    }

}