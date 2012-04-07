<?php

use History\Bus;

Bus::listen('Application\Events\V1\LanguageRegistered', function($event) {
	DB::table('languages')->insert((array) $event);
});

Bus::listen('Application\Events\V1\LanguageUpdated', function($event) {
	DB::table('languages')->where_uuid($event->uuid)->update((array) $event);
});

Bus::listen('Application\Events\V1\LanguageDeleted', function($event) {
	DB::table('languages')->where_uuid($event->uuid)->delete();
});

/* 
	We can return some information about this eventhandler,
	so we can identify them later.
*/

return array(
	'title' => 'Language Projectors',
	'description' => 'Project language data to tables'
);