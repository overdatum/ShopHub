<?php

Autoloader::namespaces(array(
	'EventSourcing' => __DIR__ . DS . '..' . DS . 'eventsourcing'
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