<?php namespace Demo\Models;

use Demo\Model;

class User extends Model {
	
	public $attributes;

	public function __construct($attributes = array())
	{
		$this->attributes = $attributes;
	}

}