<?php


namespace Erp360\Core\Routes;

use Bramus\Router\Router;
use Erp360\Core\Helpers\RoutingInterface;
use Erp360\Core\Helpers\SiteHelper;

class Web extends BaseRouter implements RoutingInterface {

    public Router $router;

    public function __construct()
    {
        $this->router = self::getInstance();
    }

    private function middlewares(): void
    {
        $this->router->set404(function() {
            SiteHelper::renderView('errors/404');
            http_response_code(404);
        });

        $this->router->before('GET', '/admin/.*', function() {
            if(SiteHelper::isUserLoggedIn() == false){
                header('location: /auth/login');
                exit();
            }
        });

        $this->router->before('GET', '/auth/.*', function() {
            if(SiteHelper::isUserLoggedIn()){
                header('location: /admin/dashboard');
                exit();
            }
        });

    }

    public function init_routes(): void
    {

        $this->middlewares();

        $this->router->get('/', 'Erp360\Core\Controllers\HomeController@index');

        // public routes
        $this->router->mount('/auth', function() {
            $this->router->get('/login', 'Erp360\Core\Controllers\Auth\AuthController@login');
            $this->router->get('/register', 'Erp360\Core\Controllers\Auth\AuthController@register');
            $this->router->get('/forgot-password', 'Erp360\Core\Controllers\Auth\AuthController@forgot_password');
            $this->router->get('/reset/pass/token', 'Erp360\Core\Controllers\Auth\AuthController@reset_password');
        });

        // authenticated routes
        $this->router->mount('/admin', function() {
            $this->router->get('/dashboard', 'Erp360\Core\Controllers\Dashboard\DashboardController@index');
            $this->router->get('/logout', 'Erp360\Core\Controllers\Auth\AuthController@logout');
        });


    }

}
