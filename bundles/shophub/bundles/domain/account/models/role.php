<?php

use History\Eloquent\Model;

use ShopHub\Domain\Account\Events\V1\RoleCreated;
use ShopHub\Domain\Account\Events\V1\RolesAssignedToAccount;
use ShopHub\Domain\Account\Events\V1\RolesUnassignedFromAccount;

class Role extends Model {

	public static $table = 'roles';

	public $includes = array('lang');

	public function accounts()
	{
		return $this->has_many_and_belongs_to('Account');
	}

	public function lang()
	{
		return $this->has_one('RoleLang')->where_language_uuid(DB::table('languages')->where_abbreviation('dut')->first()->uuid);
	}

	public function create_event()
	{
		return new RoleCreated;
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