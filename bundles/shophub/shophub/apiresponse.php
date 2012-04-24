<?php namespace ShopHub;

class APIResponse {

	public $code;

	public $body;

	public function __construct($code, $body)
	{
		$this->code = $code;
		$this->body = $body;
	}

	public function get($key = null)
	{
		if(is_null($key))
		{
			return $this->body;
		}

		return $this->body->$key;
	}

	public function error()
	{
		return $this->code !== 200;
	}

}