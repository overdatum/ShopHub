<?php namespace Demo\Repositories;

use Laravel\Database as DB;

use Demo\Models\User;

class UserRepository {
	
	public static function find($uuid)
	{
		$user_data = DB::table('users')->where_uuid($uuid)->first();
		return new User((array) $user_data);
	}

}