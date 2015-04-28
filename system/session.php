<?php

class Session
{
	function __construct()
	{
		session_start();
	}
	
	function get($key)
	{
		if(isset($_SESSION[$key])) return $_SESSION[$key];
		return null;
	}

	function set($key, $value)
	{
		$_SESSION[$key] = $value;
	}
	
	function delete($key)
	{
		unset($_SESSION[$key]);
	}
}