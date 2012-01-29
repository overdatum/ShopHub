<?php
class Role extends Eloquent\Model {

	public function __construct()
	{
		//var_dump(func_get_args());
	}

	public function accounts()
	{
		return $this->has_and_belongs_to_many('Account');
	}

	public function lang()
	{
		$lang = DB::table('role_lang')
					->where_role_id($this->id)
					->where_language_id(Auth::user()->language_id)
					->first();

		return $lang;
	}

}