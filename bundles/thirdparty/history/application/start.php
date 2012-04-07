<?php

Autoloader::namespaces(array(
	'History' => __DIR__ . DS . '..' . DS . 'history'
));

Autoloader::map(array(
	'History\\Eloquent\\Has_Many_And_Belongs_To' => __DIR__ . DS . '..' . DS . 'history' . DS . 'eloquent' . DS . 'has_many_and_belongs_to' . EXT
));

use History\EventHandlers;

Route::controller(array(
	'history::events',
	'history::eventhandlers'
));

if($allowed_events = Input::get('allowed_events'))
{
	EventHandlers::$allowed_events = $allowed_events;
}