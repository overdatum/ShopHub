<?php namespace Demo\Events\V1;

class UserUpdated {
	
	public $uuid;
	public $full_name;

	public function __construct($uuid, $full_name)
	{
		$this->uuid = $uuid;
		$this->full_name = $full_name;
	}

}