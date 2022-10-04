<?php

namespace Erp360\Core\Routes;

use Bramus\Router\Router;
use Erp360\Core\Helpers\RoutingInterface;

class Api extends BaseRouter implements RoutingInterface {

    public Router $router;
    protected const API_VERSION = '/api/v1/';

    public function __construct()
    {
        $this->router = self::getInstance();
    }

    public function init_routes()
    {
        $this->router->get( self::API_VERSION, 'Erp360\Core\Controllers\HomeController@api_welcome');
        $this->router->post(self::API_VERSION . 'check_unique', 'Erp360\Core\Controllers\Auth\AuthController@check_unique');
        $this->router->post(self::API_VERSION . 'register', 'Erp360\Core\Controllers\Auth\AuthController@register');
        $this->router->post(self::API_VERSION . 'login', 'Erp360\Core\Controllers\Auth\AuthController@login');
        $this->router->post(self::API_VERSION . 'forgot_password', 'Erp360\Core\Controllers\Auth\AuthController@forgot_password');
        $this->router->post(self::API_VERSION . 'validate_reset_token', 'Erp360\Core\Controllers\Auth\AuthController@validate_reset_token');
        $this->router->post(self::API_VERSION . 'do_reset_password', 'Erp360\Core\Controllers\Auth\AuthController@reset_password');
    }

}
