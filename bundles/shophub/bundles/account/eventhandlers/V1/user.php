<?php

use EventSourcing\Bus;

Bus::listen('Account\Events\V1\AccountRegistered', function($event) {
	die('registered');
	DB::table('accounts')->insert((array) $event);
});

Bus::listen('Account\Events\V1\AccountUpdated', function($event) {
	DB::table('accounts')->where_uuid($event->uuid)->update((array) $event);
});

Bus::listen('Account\Events\V1\AccountDeleted', function($event) {
	DB::table('accounts')->where_uuid($event->uuid)->delete();
});

/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'Account Projectors',
	'description' => 'Project account data to tables'
);