<?php

use History\Bus;

Bus::listen('es: Demo\Events\V1\UserRegistered', function($event) {
	$row = array(
		'uuid' => $event->uuid,
		'full_name' => $event->full_name,
		'version' => $event->version
	);

	DB::table('users')->insert($row);
});

Bus::listen('es: Demo\Events\V1\UserUpdated', function($event) {
	$row = array(
		'uuid' => $event->uuid,
		'full_name' => $event->full_name,
		'version' => $event->version
	);

	DB::table('users')->where_uuid($event->uuid)->update($row);
});

Bus::listen('es: Demo\Events\V1\UserDeleted', function($event) {
	DB::table('users')->where_uuid($event->uuid)->delete();
});


/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'User Projectors',
	'description' => 'Project user data to tables'
);