<?php

namespace Erp360\Core\Controllers;

use Erp360\Core\Routes\BaseRouter;

class Controller extends BaseRouter {

    public $router;

    public function __construct()
    {
        $this->router = static::getInstance();
    }

    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }


}