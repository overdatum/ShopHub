<?php
class Role extends Eloquent\Model {

	public function role_lang()
	{
		$this->has_one('Role_lang');
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