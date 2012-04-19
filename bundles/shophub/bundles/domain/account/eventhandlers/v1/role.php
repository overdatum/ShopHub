<?php

use History\Bus;

Bus::listen('ShopHub\Domain\Account\Events\V1\RoleCreated', function($event) {
	DB::table('roles')->insert((array) $event);
});

/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'Role Projectors',
	'description' => 'Project role data to tables'
);