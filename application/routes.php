<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your applications using Laravel's RESTful routing, and it
| is perfectly suited for building both large applications and simple APIs.
| Enjoy the fresh air and simplicity of the framework.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Router::register('GET /hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Router::register('GET /hello, GET /world', function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Router::register('GET /hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in "before" and "after" filters are called before and
| after every request to your application, and you may even create other
| filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Filter::register('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Filter::register('before', function()
{
	// Do stuff before every request to your application...
});

Filter::register('after', function()
{
	// Do stuff after every request to your application...
});

Filter::register('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Filter::register('auth', function()
{
	if (Auth::guest()) return Redirect::to('account/login');
});

Filter::register('can', function($action, $resource) {
	if ( ! Authority::can($action, $resource)) return Redirect::to('home');
});

// Routes
Router::register('GET /', 'frontend.home@index');
Router::register(array('GET /home/(:any?)', 'PUT /home/(:any?)', 'DELETE /home/(:any?)', 'POST /home/(:any?)'), 'frontend.home@(:1)', array('a', 'b', 'c'));
Router::register(array('GET /account/(:any?)', 'PUT /account/(:any?)', 'DELETE /account/(:any?)', 'POST /account/(:any?)'), 'frontend.account@(:1)');
Router::register('GET /admin', 'admin.dashboard@index');

View::composer('layouts.default', function($view)
{
	Asset::container('header')->add('jquery', 'js/jquery.min.js');
	Asset::container('header')->add('bootstrap', 'bootstrap/bootstrap.css');
	Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-buttons.js', 'jquery');
	Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-dropdown.js', 'jquery');
	Asset::container('header')->add('main', 'css/main.css');

	$view->footer = View::make('partials.footer');
});