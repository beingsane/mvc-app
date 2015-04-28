<?php

class User extends Base_Model
{
    public function __construct()
    {
        $this->properties = [
            'id' => 0,
            'email' => '',
            'password' => '',
            'name' => '',
            'registration_code' => '',
        ];
    }
    
	public function isGuest()
	{
		return ($this->id == 0);
	}
}