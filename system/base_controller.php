<?php

class Base_Controller
{
	protected $get = [];
	protected $post = [];
	
	protected $app = null;
	
	
	function __construct($get, $post)
	{
		$this->get = $get;
		$this->post = $post;
		$this->app = Application::$app;
	}
	
	
	function beforeCallAction($action_name)
	{
		// stub
	}
	
	
	/**
	 * Includes template file and passes variables from $data there
	 * @var string $file - template file (without extention)
	 * @var array $data - array of variables
	 */
	function render($file, $data = array())
	{
		$class_name = get_class($this);
		$path = $this->app->getClassPath($class_name);
		$template_file_name = 'views/'.$path.'/'.$file.'.htm';
        
        // auto-include css and js for this template
        $css_file_name = $path.'/css/'.$file.'.css';
        if (file_exists('views/'.$css_file_name)) {
            $this->app->addCss($css_file_name);
        }
        
        $js_file_name = $path.'/js/'.$file.'.css';
        if (file_exists('views/'.$js_file_name)) {
            $this->app->addJs($js_file_name);
        }
		
		foreach($data as $key => $value)
		{
			$$key = $value;
		}
		
		include($template_file_name);
	}
}
