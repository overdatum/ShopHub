<?php namespace Demo\Events\V1;

class UserDeleted {
	
	public $uuid;

	public function __construct($uuid)
	{
		$this->uuid = $uuid;
	}

}