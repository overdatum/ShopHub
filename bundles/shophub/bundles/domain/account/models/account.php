<?php

use History\UUID;
use History\Eloquent\Model;

use ShopHub\Domain\Account\Events\V1\AccountRegistered;
use ShopHub\Domain\Account\Events\V1\AccountUpdated;
use ShopHub\Domain\Account\Events\V1\AccountDeleted;

class Account extends Model {

	public static $timestamps = true;

	public static $table = 'accounts';

	public static $accessible = array('name', 'email', 'language_uuid', 'uuid');

	public static $versioned = true;

	public static $rules = array(
		'email' => 'required|email',
		'name' => 'required',
	);

	public static $hidden = array('password', 'language_uuid');

	public function language()
	{
		return $this->belongs_to('Language', 'language_uuid');
	}

	public function roles()
	{
		return $this->has_many_and_belongs_to('Role');
	}

	public function create_event()
	{
		return new AccountRegistered;
	}

	public function update_event()
	{
		return new AccountUpdated;
	}

	public function delete_event()
	{
		return new AccountDeleted;
	}

	/**
	 * Check if the account has a relation with the given role
	 *
	 * @param	string	$key	the role key
	 * @return	boolean
	 */
    public function has_role($key)
    {
        return is_null($this->roles()->where_name($key)->first());
    }

	/**
	 * Check if the account has a relation with any of the given roles
	 *
	 * @param	array	$keys	the role keys
	 * @return	boolean
	 */
    public function has_any_role($keys)
    {
        if( ! is_array($keys))
        {
            $keys = func_get_args();
        }

        return is_null($this->roles()->where('name', 'IN', $keys)->first());
    }

}