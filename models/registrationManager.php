<?php

class RegistrationManager
{
    private $app = null;
	
	
	public function __construct()
	{
		$this->app = Application::$app;
	}
    
    
    public function register($userData)
    {
        do {
            $errors = $this->validateRegistrationData($userData);
            if (count($errors) > 0) {
                break;
            }
            
            
            if($this->checkAlreadyExists($userData)) {
                $errors = ['EMAIL_ALREADY_EXISTS'];
                break;
            }
            
            $newUser = $this->createNewUser($userData);
            if($newUser->id > 0) {
                if(!$this->sendConfirmEmail($newUser)) {
                    $errors = ['EMAIL_SEND_ERROR'];
                    break;
                }
            } else {
                $errors = ['CREATE_USER_ERROR'];
                break;
            }
        } while(0);
        
        return $errors;
    }
    
    
    private function checkAlreadyExists($userData)
    {
        $users = $this->app->repository->filter('users', ['email' => $userData['email']], 1);
        $exists = (count($users) > 0);
        return $exists;
    }
    
    
    private function validateRegistrationData($data)
    {
        $errors = [];
        
        if ($data['name'] == '') {
            $errors[] = 'NAME_EMPTY';
        }
        
        if ($data['email'] == '') {
            $errors[] = 'EMAIL_EMPTY';
        }
        
        if ($data['password'] == '') {
            $errors[] = 'PASSWORD_EMPTY';
        }
        
        if ($data['password'] != $data['password2']) {
            $errors[] = 'PASSWORDS_NOT_EQUAL';
        }
        
        $email_reg = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";
        if (!preg_match($email_reg, $data['email'])) {
            $errors[] = 'INVALID_EMAIL';
        }
        
        return $errors;
    }
    
    
    private function createNewUser($userData)
    {
        $newUser = $this->app->repository->createObject('User');
        $newUser->name = $userData['name'];
        $newUser->email = $userData['email'];
        $newUser->password = Application::getPasswordHash($userData['password']);
        $newUser->registration_code = rand(10000000, 99999999);
        $this->app->repository->save('users', $newUser);
        
        return $newUser;
    }
    
    
    private function sendConfirmEmail($user)
    {
        $appConfig = $this->app->getConfig();
        
        $mailer = new PHPMailer();
        $mailer->setFrom('noreply@test.local', $appConfig['site_name']);
        $mailer->addAddress($user->email);
        
        $message = 'Вы зарегистрировались на сайте <a href="'.$appConfig['site_url'].'">'.$appConfig['site_name'].'</a>';
        $message .= '<br/>Для завершения регистрации пройдите по ссылке:';
        $registration_link = $appConfig['site_url'].'/main/confirm_registration/?code='.$user->registration_code;
        $message .= '<br/><a href="'.$registration_link.'">'.$registration_link.'</a>';
        $mailer->Subject = 'Регистрация на сайте '.$appConfig['site_name'];
        $mailer->Body = $message;
        
        return $mailer->send();
    }
    
    
    public function confirmRegistration($registration_code)
    {
        $user_rows = $this->app->repository->filter('users', ['registration_code' => $registration_code], 1);
        if(!$user_rows) {
            return false;
        }
        
        $user = $this->app->repository->createObject('User', $user_rows[0]);
        $user->registration_code = '';
        $this->app->repository->save('users', $user);
        
        // авто-логин после подтверждения регистрации
        $this->app->doLogin($user->getProperties());
        
        return true;
    }
}
