<?php

namespace Erp360\Core\Routes;

use Bramus\Router\Router;

class BaseRouter {

    private static $routerInstance = null;

    public static function getInstance()
    {
        if (self::$routerInstance == null) {
            self::$routerInstance = new Router();
        }

        return self::$routerInstance;
    }

}
