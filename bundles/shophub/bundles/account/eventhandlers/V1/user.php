<?php

use History\UUID;
use History\Bus;

Bus::listen('Account\Events\V1\AccountRegistered', function($event) {
	DB::table('accounts')->insert((array) $event);
});

Bus::listen('Account\Events\V1\AccountUpdated', function($event) {
	DB::table('accounts')->where_uuid($event->uuid)->update((array) $event);
});

Bus::listen('Account\Events\V1\AccountDeleted', function($event) {
	DB::table('accounts')->where_uuid($event->uuid)->delete();
});

Bus::listen('Account\Events\V1\RolesAssignedToAccount', function($event) {
	$rows = array();
	foreach($event->role_uuids as $role_uuid)
	{
		$rows[] = array(
			'uuid' => UUID::generate(),
			'account_uuid' => $event->uuid,
			'role_uuid' => $role_uuid,
			'created_at' => $event->created_at,
			'updated_at' => $event->updated_at
		);
	}
	DB::table('account_role')->insert($rows);
});

Bus::listen('Account\Events\V1\RolesUnassignedFromAccount', function($event) {
	DB::table('account_role')->where_account_uuid($event->uuid)->where_in('role_uuid', $event->role_uuids)->delete();
});

/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'Account Projectors',
	'description' => 'Project account data to tables'
);