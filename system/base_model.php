<?php

interface IBase_Model
{
    function setProperties($properties);
    function getProperties();
}

class Base_Model implements IBase_Model
{
    protected $properties = [];
    
    public function setProperties($properties)
    {
        $this->properties = $properties;
    }
    
    public function getProperties()
    {
        return $this->properties;
    }
    
    public function __get($property_name)
    {
        if (array_key_exists($property_name, $this->properties)) {
            return $this->properties[$property_name];
        }
        
        return null;
    }
    
    public function __set($property_name, $value)
    {
        $this->properties[$property_name] = $value;
    }
}
