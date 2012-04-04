<?php

// all we need is the classes dir, Anbu is tiny!
Autoloader::directories(array(
	Bundle::path('anbu').'classes'
));

// pass laravel log entries to anbu
Event::listen('laravel.log', 'Anbu::log');

// pass executed SQL queries to anbu
Event::listen('laravel.query', 'Anbu::sql');
