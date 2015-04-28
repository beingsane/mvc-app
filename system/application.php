<?php

require_once('base_controller.php');
require_once('base_model.php');
require_once('session.php');

class Application
{
	public static $app = null;
	
	static function getApp()
	{
		if(!self::$app)
		{
			include('config/config.php');
			self::$app = new Application($config);
		}
		return self::$app;
	}
	
	// -----------------------------------------------------------------------------
	
	private $config = [];
	private $db = null;
	
	private $title = 'Тест';
	private $cssFiles = [];
	private $jsFiles = [];
	
	public $session = null;
	public $user = null;
    public $repository = null;
	
	
	private function __construct($config)
	{
		$this->config = $config;
	}
    
	
	/**
	 * Autoload model classes
	 */
	function autoload($class_name)
	{
		$path = 'models/'.$this->getClassPath($class_name).'.php';
		if(file_exists($path))
		{
			require_once($path);
		}
	}
	
	
	/**
	 * Calls front controller
	 */
	function start()
	{
		// register autoload function for loading models
		spl_autoload_register([$this, 'autoload']);
		
		// create database object
		$config = $this->config;
		require_once('db/PDOWrapper.php');
		require_once('db/'.$config['db']['driver'].'.php');
		$this->db = new $config['db']['driver'] ($config['db']);
		
        // create repository object
		require_once('system/repository.php');
		$this->repository = new Repository();
        
		// create session and user
		$this->session = new Session();

		$user_id = $this->session->get('user_id');
		$this->user = $this->repository->find('User', 'users', $user_id);

		
		// URI struct:
		// /controller/action/?var1=value1&var2=value2
		
		list($path_str, $filter_str) = explode('?', $_SERVER['REQUEST_URI']);
		unset($filter_str);
		
		// skip leading '/'
		$path_str = substr($path_str, 1);
		
		$path = explode('/', $path_str);
		if(empty($path[0])) $path[0] = 'main';
		if(empty($path[1])) $path[1] = 'index';
		
		$this->call($path[0], $path[1], $_GET, $_POST);
	}
	
	
	/**
	 * Simple front controller
	 * calls $action_name in $controller_name
	 * also this function allows to make multi-MVC struct by performing $app->call('controller', 'action') in any view
	 */
	function call($controller_name, $action_name, $get = [], $post = [])
	{
		// here must be only letters, underscores and numbers
		$controller_name = preg_replace('/[^a-zA-Z\_\d]+/', '', $controller_name);
		$action_name = preg_replace('/[^a-zA-Z\_\d]+/', '', $action_name);
		
		
		// look for controller
		// /path_to_controller_name/action_name/ -> controllers/path/to/controller/name.php
		// /main/index/ -> controllers/main.php
		$controller_info = explode('_', $controller_name);
		$controller_file_path = 'controllers/'.implode('/', $controller_info).'.php';
		if(!file_exists($controller_file_path))
		{
			throw new Exception('File not found');
		}
		require_once($controller_file_path);
		
		// /path_to_controller_name/action_name/ -> Path_To_Controller_Name_Controller
		// /main/index/ -> Main_Controller
		$this->ucfirstArray($controller_info);
		$class_name = implode('_', $controller_info).'_Controller';
		
		
		
		$controller = new $class_name($get, $post);
		
		// look for action
		// /path_to_controller_name/action_name/ -> actionName()
		// /main/index/ -> index()
		$action_info = explode('_', $action_name);
		$this->ucfirstArray($action_info);
		$action_name = 'action'.implode('', $action_info);
		if(!method_exists($controller, $action_name))
		{
			throw new Exception('File not found');
		}
		
		// make a call
		ob_start();
		if(call_user_func([$controller, 'beforeCallAction'], $action_name))
		{
			call_user_func([$controller, $action_name]);
		}
		$content = ob_get_clean();
		
		$this->renderContent($content);
	}
	
	
	function renderContent($content)
	{
		if(@!empty($_SERVER['HTTP_X_REQUESTED_WITH']))
		{
			// if it is ajax call, just send content
			echo $content;
		}
		else
		{
			// if it is usual request, render the whole page
            
			// let's assume, that we have only one layout
			include('layouts/layout.php');
		}
	}
    
    
	function getDB()
	{
		return $this->db;
	}
	
	
	function getConfig()
	{
		return $this->config;
	}
	
	
	function ucfirstArray(&$arr)
	{
		foreach($arr as $i => $word)
		{
			$arr[$i] = ucfirst($word);
		}
	}
	
    
	function getClassPath($class_name)
	{
		$class_name_info = explode('_', strtolower($class_name));
		
		// skip last string 'Controller'
		if($class_name_info[count($class_name_info) - 1] == 'controller')
		{
			array_pop($class_name_info);
		}
		$path = implode('/', $class_name_info);
		
		return $path;
	}
	
	
	function redirect($path)
	{
		header('Location: '.$path);
		die;
	}
	
	
	function setTitle($title)
	{
		$this->title = $title;
	}
	
	
	function addCss($path)
	{
		$this->cssFiles[] = '/views/'.$path;
	}
	
	
	function addJs($path)
	{
		$this->jsFiles[] = '/views/'.$path;
	}
    
    /*
        It's just simple framework, so we don't take out login functionality into separate class
    */
    static function getPasswordHash($password)
	{
		$config = Application::$app->getConfig();
		$salt = $config['password_salt'];
		$password_hash = md5($password.$salt);
		
		return $password_hash;
	}
	
	
	function login($email, $password)
	{
        $password_hash = self::getPasswordHash($password);
        $filter = ['email' => $email, 'password' => $password_hash, 'registration_code' => ''];
		$user_rows = $this->repository->filter('users', $filter, 1);
		if (!$user_rows) {
            return false;
        }

        $this->doLogin($user_rows[0]);
        return true;
	}
    
    
    function doLogin($user_row)
    {
		$user_id = $user_row['id'];
		$this->user = $this->repository->createObject('User', $user_row);
		
		$this->session->set('user_id', $user_id);
		$return_path = $this->session->get('RETURN_PATH');
		if ($return_path) {
			$this->session->delete('RETURN_PATH');
			$this->redirect($return_path);
		}
    }
	
	
	function logout()
	{
		$this->user = new User();
		$this->session->delete('user_id');
	}
}
