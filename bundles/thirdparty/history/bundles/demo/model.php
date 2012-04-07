<?php namespace Demo;

class Model {
	
	public function __get($key)
	{
		if( ! array_key_exists($key, $this->attributes)) return null;
		
		return $this->attributes[$key];
	}

}