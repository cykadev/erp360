<?php

namespace Erp360\Core\Controllers\Auth;

use Erp360\Core\Helpers\SiteHelper;
use Erp360\Core\Services\AuthService;
use Erp360\Core\Controllers\Controller;
use Erp360\Core\Helpers\BaseValidation;
use Josantonius\Session\Facades\Session;
use Respect\Validation\Exceptions\NestedValidationException;

class AuthController extends Controller {

    use BaseValidation;

    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
    }

    public function login()
    {

        if($this->isPost()){

            $v = static::getValidationInstance();

            $validator = $v::key('email_username', $v::notBlank())
            ->key('password', $v::notBlank());

            if(!$validator->validate($_POST)){
                $data['message'] = 'Parameter missing!';
                return SiteHelper::JsonReponse($data);
            }

            $data = $this->authService->do_login($_POST);
            return SiteHelper::JsonReponse($data);

        }

        return SiteHelper::renderView('auth/login');

    }

    public function forgot_password()
    {
        if($this->isPost()){
            $email = filter_input(INPUT_POST, 'email');
            $v = static::getValidationInstance();

            if($v::notBlank()->email()->validate($email)){
                $response = $this->authService->setup_forgot_password($email);
                return SiteHelper::JsonReponse($response);
            }

        }
        
        return SiteHelper::renderView('auth/forgot-password');
    }

    public function check_unique()
    {
        $field = filter_input(INPUT_POST, 'field');
        $data = filter_input(INPUT_POST, 'data');
        $count = $this->authService->uniqueCheck($field, $data);
        return SiteHelper::JsonReponse($count);
    }

    public function register()
    {

        $data = [
            'status' => false
        ];

        if($this->isPost()){

            $v = static::getValidationInstance();

            $validator = $v::key('username', $v::notBlank()->stringType()->length(6))
            ->key('email', $v::notBlank()->email()->length(null, 255))
            ->key('password', $v::notBlank()->stringType()->length(6));
            
            try {

                $validator->assert($_POST);

                if($this->authService->complete_registration($_POST)){
                    $data['success'] = 'Registeration successfull.';
                    $data['status'] = true;
                }

            } catch(NestedValidationException $exception) {
                $data['errors'] = $exception->getMessages();
            }

            Session::set('notify', ['message' => 'Registration sucessfull!', 'notifyClass' => 'secondary']);
            return SiteHelper::JsonReponse($data);

        }

        return SiteHelper::renderView('auth/register');

    }

    public function reset_password()
    {

        if($this->isPost()){
            $token = filter_input(INPUT_POST, 'token');
            $password = filter_input(INPUT_POST, 'password');
            $v = static::getValidationInstance();

            if($v::notBlank()->length(6, 32)->validate($password) && $v::notBlank()->validate($token)){
                $response = $this->authService->change_password($token, $password);
                return SiteHelper::JsonReponse($response);
            }
        }

        return SiteHelper::renderView('auth/reset-password');
    }

    public function validate_reset_token()
    {

        sleep(1);

        $token = filter_input(INPUT_POST, 'token');
        $validToken = $this->authService->validate_token($token);

        $response = [
            'status' => false,
            'error' => '',
            'success_msg' => ''
        ];

        if($validToken){
            $response['status'] = true;
            $response['success_msg'] = 'valid';
        }else{
            $response['error'] = 'Token expired';
        }

        return SiteHelper::JsonReponse($response);

    }


    public function logout()
    {
        Session::clear();
        header('location: /auth/login');
    }


}