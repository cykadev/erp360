<?php

namespace Erp360\Core\Services;

use Erp360\Core\Helpers\SiteHelper;
use Erp360\Core\Mails\ForgotPasswordMail;
use Josantonius\Session\Facades\Session;

class AuthService extends BaseService {
    
    public function compareHash(string $pass, string $hashedPassword): bool
    {
        return password_verify($pass, $hashedPassword);       
    }

    public function hashPassword(string $pass): string
    {
        return password_hash($pass, PASSWORD_BCRYPT);
    }

    public function uniqueCheck(string $field, string $data): bool
    {
        $searchField = 'user_name';
        if($field == 'email'){
            $searchField = 'email';
        }

        $stmt = $this->database->prepare("SELECT COUNT(*) FROM users WHERE $searchField = ?");
        $stmt->execute(array($data));
        return $stmt->fetchColumn() === 0;
    }

    public function complete_registration(array $params): int
    {
        $stmt = $this->database->prepare("INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([
            $params['username'],
            $params['email'],
            $this->hashPassword($params['password'])
        ]);

        return $this->database->lastInsertId();
    }

    public function do_login(array $params): array
    {

        $stmt = $this->database->prepare("SELECT * FROM users WHERE email = :user OR user_name = :user");
        $stmt->execute([':user' => $params['email_username']]);

        $user = $stmt->fetchObject();
        $stmt->closeCursor();

        $response = [
            'status' => false,
            'error' => ''
        ];

        if($user){
            if(!$this->compareHash($params['password'], $user->password)){
                $response['error'] = "Invalid credentials!";
            }else{
                // if($user->can_login == 0){
                //      $response['error'] = "Your account is disabled.";
                // }else{
                //     Session::set('login', ['user_id' => $user[0]['id'], 'email' => $user[0]['email']]);
                //     $response['status'] = true;
                // }

                Session::set('login', ['user_id' => $user->id, 'email' => $user->email]);
                $response['status'] = true;

            }
        }else{
            $response['error'] = "Invalid credentials!";
        }

        return $response;

    }

    public function setup_forgot_password(string $email): array
    {

        $response = [
            'status' => false,
            'success_msg' => '',
            'error' => ''
        ];

        // get user id from token
        $smth = $this->database->prepare("SELECT * FROM users WHERE email = ?");
        $smth->execute([$email]);

        $user = $smth->fetchObject();
        $smth->closeCursor();

        if($user){

            $tokenQuery = $this->database->prepare("SELECT * FROM user_tokens WHERE user_id = ? AND mode = 'FORGOT_PASSWORD'");
            $tokenQuery->execute([$user->id]);

            $token = $tokenQuery->fetchObject();
            $tokenQuery->closeCursor();

            $tknstr = SiteHelper::generateRandomString(120);

            if($token){
                $tokenUpdateQuery = $this->database->prepare("UPDATE user_tokens SET token = ? WHERE id = ?");
                $tokenUpdateQuery->execute([$tknstr, $token->id]);
            }else{
                $tokenUpdateQuery = $this->database->prepare("INSERT INTO user_tokens (user_id, token) VALUES (?, ?)");
                $tokenUpdateQuery->execute([$user->id, $tknstr]);
            }

            $mail = new ForgotPasswordMail($user->email, [
                'name' => $user->email,
                'token' => $tknstr,
            ]);
            
            if($mail->sendMail()){
                $response['status'] = true;
                $response['success_msg'] = 'Password reset link has been sent to your email address.';
            }

            $response['error'] = '';

        }else{
            $response['error'] = 'Unable to find any user associated with ' . $email;
        }

        return $response;

    }

    public function change_password(string $token, string $password): array
    {

        $res = [
            'status' => false,
            'error' => '',
            'success_msg' => '',
        ];

        if(!$this->validate_token($token)){
            $res['error'] = 'Token expired';
        }else{

            // get user id from token
            $first = $this->database->prepare("SELECT user_id FROM user_tokens WHERE token = ? AND mode = 'FORGOT_PASSWORD'");
            $first->execute([$token]);

            $user_id = $first->fetchColumn();
            $first->closeCursor();

            // update password
            $second = $this->database->prepare("UPDATE users SET password = ? WHERE id = ?");
            $second->execute([$this->hashPassword($password), $user_id]);
            
            // delete token from db
            $third = $this->database->prepare("DELETE FROM user_tokens WHERE token = ? AND mode = 'FORGOT_PASSWORD'");
            $third->execute([$token]);

            $res['status'] = true;
            $res['success_msg'] = 'Password updated';

        }

        return $res;

    }

    public function validate_token(string $token): bool
    {
        $stmt = $this->database->prepare("SELECT COUNT(*) FROM user_tokens WHERE token = ? AND mode = 'FORGOT_PASSWORD'");
        $stmt->execute(array($token));
        return $stmt->fetchColumn() > 0;
    }



}