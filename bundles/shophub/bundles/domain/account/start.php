<?php

use History\EventHandlers;

Autoloader::directories(array(
	__DIR__ . DS . 'models'
));

EventHandlers::register(__DIR__ . DS . 'eventhandlers');