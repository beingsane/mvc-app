<?php

class Main_Controller extends Base_Controller
{
    public function beforeCallAction($action_name)
    {
        // настройка разделов, доступных только для зарегистрированных пользователей
        if ($this->app->user->isGuest() && in_array($action_name, ['profile'])) {
            $this->app->session->set('RETURN_PATH', $_SERVER['REQUEST_URI']);
            $this->app->redirect('/main/login_form');
            return false;
        }
        
        return true;
    }
    
    
    public function actionIndex()
    {
        $this->render('index');
    }
    
    
    public function actionAbout()
    {
        $this->render('about');
    }
    
    
    public function actionProfile()
    {
        $user = $this->app->user;
        $this->render('profile', ['user' => $user]);
    }
    
    
    public function actionLoginForm()
    {
        $this->render('loginForm');
    }
    
    
    public function actionLogin()
    {
        if (! $this->app->login($this->post['login_form']['email'], $this->post['login_form']['password'])) {
            $login_status = 'FAILED';
        } else {
            $login_status = 'OK';
        }
        $this->app->session->set('LOGIN_STATUS', $login_status);
        
        if ($login_status == 'OK' && (!$return_path || $return_path == '/main/login_form')) {
            $return_path = $this->app->session->get('RETURN_PATH');
            $return_path = '/';
        } else if ($login_status == 'FAILED') {
            $return_path = '/main/login_form';
        }
        
        $this->app->redirect($return_path);
    }
    
    
    public function actionLogout()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }
        
        $this->app->logout();
        $this->app->session->delete('LOGIN_STATUS');
        $this->app->session->delete('RETURN_PATH');
        
        $this->app->redirect('/');
    }
    
    
    public function actionRegistrationForm()
    {
        $this->render('registrationForm');
    }
    
    
    public function actionRegister()
    {
        $data = $this->post['registration_form'];
        $reg = new RegistrationManager();
        $errors = $reg->register($data);
        
        if (count($errors) > 0) {
            $this->render('registrationForm', ['data' => $data, 'errors' => $errors]);
        } else {
            $this->render('registrationSuccess');
        }
    }
    
    
    public function actionConfirmRegistration()
    {
        $reg = new RegistrationManager();
        $code = $this->get['code'];
        if ($reg->confirmRegistration($code)) {
            $data = ['confirm_status' => 'OK'];
        } else {
            $data = ['confirm_status' => 'FAILED'];
        }
        
        $this->render('confirmRegistration', $data);
    }
}
