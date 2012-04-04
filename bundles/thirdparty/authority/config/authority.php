<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Initialize User Permissions Based On Roles
	|--------------------------------------------------------------------------
	|
	| This closure is called by the Authority\Ability class' "initialize" method
	|
	*/

	'initialize' => function($account)
	{
		Authority::action_alias('manage', array('create', 'read', 'update', 'delete'));
		Authority::action_alias('moderate', array('update', 'delete'));

		if(count($account->roles) == 0) return false;
/*
		if($account->has_role('store_owner'))
		{
			Authority::allow('manage', 'Store', function($store) use ($account)
			{
				return DB::table('stores')->where_user_id($account->id)->first();
			});
		}

		if($account->has_role('organisation'))
		{
			Authority::allow('manage', 'Organisation', function($organisation) use ($account)
			{
				return DB::table('organisation')->where_user_id($account->id)->first();
			});
		}

		if($account->has_any_role('store_owner', 'manufacturer', 'reseller'))
		{
			// Store_owners, Manufacturers and Resellers can "manage" their user
			Authority::allow('moderate', 'User', function ($that_account) use ($account)
			{
				return $that_account->id == $account->id;
			});
		}

		if($account->has_role('admin'))
		{*/
			Authority::allow('manage', 'all');
			/*Authority::deny('delete', 'User', function ($that_account) use ($account)
			{
				return $that_account->id == $account->id;
			});
		}*/
	}

);