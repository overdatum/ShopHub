<?php

Autoloader::namespaces(array(
	'Demo' => __DIR__
));

History\EventHandlers::register(array(
	__DIR__.DS.'eventhandlers'
));

Route::controller('demo::users');