<?php

namespace Source\Controller;

use Source\Core\Controller;


class Web extends Controller
{
    public function __construct($pathToViews = null)
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_THEME . "/");
    }

    public function home()
    {
        echo CONF_MAIL_HOST;
    }

    public function error($data)
    {
        var_dump($data);
    }
}