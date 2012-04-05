<?php

use EventSourcing\Eloquent\Model;

use Account\Events\V1\RolesAssignedToAccount;
use Account\Events\V1\RolesUnassignedFromAccount;

class Role extends Model {

	public static $table = 'roles';

	public $includes = array('lang');

	public function accounts()
	{
		return $this->has_many_and_belongs_to('Account');
	}

	public function lang()
	{
		return $this->has_one('RoleLang');//->where_language_id(1);
	}

	public function attach_to_account_event()
	{
		return new RolesAssignedToAccount;
	}

	public function detach_from_account_event()
	{
		return new RolesUnassignedFromAccount;
	}
}