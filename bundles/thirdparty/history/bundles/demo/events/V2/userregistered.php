<?php namespace Demo\Events\V2;

class UserRegistered {
	
	public $uuid;
	public $first_name;
	public $last_name;

	public function __construct($uuid, $first_name, $last_name)
	{
		$this->uuid = $uuid;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
	}

}