<?php

namespace Erp360\Core\Helpers;

use Josantonius\Session\Facades\Session;

class SiteHelper {

    public static function renderView( string $path, $data = null ): string | \Exception 
    {
        $file = realpath(__DIR__ . "/../../views/$path.php");

        if(file_exists($file)){
            return include $file;
        }

        throw new \Exception("view not found!", 1);
    }

    public static function renderEmail( string $path, $data = [] ): string
    {
        if (is_array($data) && !empty($data)) {
            extract($data);
        }
        
        $file = realpath(__DIR__ . "/../../views/$path.php");
        
        ob_start();
        if(file_exists($file)){
            include_once($file);
            return ob_get_clean();
        }

        throw new \Exception("view not found!", 1);

    }

    public static function renderSessionNotification()
    {
        if(Session::has('notify')){
            $session = Session::get('notify');
            echo '<div class="alert alert-' . $session['notifyClass'] .' alert-dismissible" role="alert">
            ' . $session['message'] .'
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }


    public static function assets( string $path ): string
    {
        return $_ENV['BASE_URL'] . 'assets/' . $path;
    }

    public static function navigator( string $url ): string
    {
        return $_ENV['BASE_URL'] . $url;
    }

    public static function apiNavigator( string $url ): string
    {
        return $_ENV['BASE_URL'] . 'api/v1/' . $url;
    }

    public static function extractPageReponseKeys( array $data ): array
    {
        $inputs = isset($data['inputs']) ? $data['inputs'] : [];
        $errors = isset($data['errors']) ? $data['errors'] : [];
        $success = isset($data['success']) ? $data['success'] : '';

        return ['inputs' => $inputs, 'errors' => $errors, 'success' => $success ];
    }

    public static function JsonReponse( mixed $data ): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, JSON_FORCE_OBJECT);
    }

    public static function isUserLoggedIn(): bool
    {
        return Session::has('login');
    }

    public static function generateRandomString(int $length = 50): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}