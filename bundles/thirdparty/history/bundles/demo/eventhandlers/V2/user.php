<?php

use History\Bus;

Bus::listen('es: Demo\Events\V2\UserRegistered', function($event) {
	$row = array(
		'uuid' => $event->uuid,
		'first_name' => $event->first_name,
		'last_name' => $event->last_name,
		'version' => $event->version
	);
	
	DB::table('users')->insert($row);
});

Bus::listen('es: Demo\Events\V2\UserUpdated', function($event) {
	$row = array(
		'uuid' => $event->uuid,
		'first_name' => $event->first_name,
		'last_name' => $event->last_name,
		'version' => $event->version
	);

	DB::table('users')->where_uuid($event->uuid)->update($row);
});


/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'User Projectors',
	'description' => 'Project user data to tables'
);