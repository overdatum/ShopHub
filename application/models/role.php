<?php
class Role extends Eloquent {

	public $includes;

	public function __construct()
	{
		$this->includes = array('lang' => function($query)
		{
			$query->where_language_id(1);
		});
	}

	public function accounts()
	{
		return $this->has_many_and_belongs_to('Account');
	}

	public function lang()
	{
		return $this->has_one('RoleLang');
	}

}