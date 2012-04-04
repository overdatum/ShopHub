<?php

use EventSourcing\Model;
use Account\Events\V1\AccountRegistered;
use Account\Events\V1\AccountUpdated;
use Account\Events\V1\AccountDeleted;
use Account\Events\V1\RolesAssignedToAccount;
use Account\Events\V1\RolesUnassignedFromAccount;

class Account extends Model {

	public static $timestamps = true;

	public static $table = 'accounts';

	public static $accessible = array('name', 'email', 'password');

	public $rules = array(
		'email' => 'required|email',
		'name' => 'required',
	);

	public function create_event()
	{
		return new AccountRegistered(14, $this->name, $this->email);
	}

	public function update_event()
	{
		return new AccountUpdated($this->id, $this->name, $this->email);
	}

	public function delete_event()
	{
		return new AccountDeleted($this->id);
	}

	public function roles()
	{
		return $this->has_many_and_belongs_to('Role', 'accounts_roles');
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

	public function validate_and_insert()
	{
		$this->rules['password'] = 'required';
		$validator = new Validator(Input::all(), $this->rules);

		if ($validator->valid())
		{
			$this->email = Input::get('email');
			$this->password = Hash::make(Input::get('password'));
			$this->name = Input::get('name');
			$this->save();

			if(Input::has('role_ids'))
			{
				foreach(Input::get('role_ids') as $role_id)
				{
					if($role_id == 0) continue;

					DB::table('accounts_roles')
						->insert(array('account_id' => $this->id, 'role_id' => $role_id));
				}
			}
		}

		return $validator->errors;
	}

	public function validate_and_update()
	{
		$validator = new Validator(Input::all(), $this->rules);
		if ($validator->valid())
		{
			DB::table('accounts_roles')->where_account_id($this->id)->delete();

			if(Input::has('role_ids'))
			{
				foreach (Input::get('role_ids') as $role_id) {
					if($role_id == 0) continue;

					DB::table('accounts_roles')
						->insert(array('account_id' => $this->id, 'role_id' => $role_id));
				}
			}

			$this->email = Input::get('email');
			if($password = Input::get('password')) $this->password = Hash::make($password);
			$this->name = Input::get('name');
			$this->save();
		}

		return $validator->errors;
	}


}