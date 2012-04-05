<?php

Autoloader::namespaces(array(
	'EventSourcing' => __DIR__ . DS . '..' . DS . 'eventsourcing'
));

Autoloader::map(array(
	'EventSourcing\\Eloquent\\Has_Many_And_Belongs_To' => __DIR__ . DS . '..' . DS . 'eventsourcing' . DS . 'eloquent' . DS . 'has_many_and_belongs_to' . EXT
));

use EventSourcing\EventHandlers;

Route::controller(array(
	'eventsourcing::events',
	'eventsourcing::eventhandlers'
));

if($allowed_events = Input::get('allowed_events'))
{
	EventHandlers::$allowed_events = $allowed_events;
}