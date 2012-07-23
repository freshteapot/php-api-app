<?php
namespace Freshteapot\Library;

class OneTime
{
    private $data = array();
    function __construct(Array $options= array())
    {
        foreach ($options as $key=>$option) {
            $this->$key = (string)$option; 
        }
    }

    function __set($name, $value)
    {
        if (isset($this->data[$name]) || array_key_exists($name, $this->data)) {
            throw new \Exception("Onetime");
        }
        $this->data[$name] = (string)$value;
    }

    function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        throw new \Exception("Onetime");
    }
}
