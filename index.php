<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

class Application {

    public $webRoutes;
    public $apiRoutes;

    public function __construct()
    {
        // load env variables
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->safeLoad();

        \Josantonius\Session\Facades\Session::start([
            'cookie_lifetime' => 86400,
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax'
        ]);

        // load routes
        $this->webRoutes = new \Erp360\Core\Routes\Web();
        $this->apiRoutes = new \Erp360\Core\Routes\Api();
    }

    public function run()
    {
        // run routes
        $this->webRoutes->init_routes();
        $this->apiRoutes->init_routes();

        \Erp360\Core\Routes\BaseRouter::getInstance()->run();

    }

}


$app = new Application();
$app->run();