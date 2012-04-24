<?php namespace ShopHub\Profiling;

use Laravel\Event;
use Laravel\Request;
use Laravel\Profiling\Profiler as Laravel_Profiler;

class Profiler extends Laravel_Profiler {

	protected static $data = array('queries' => array(), 'logs' => array(), 'api_calls' => array());

	public static function render($response)
	{
		// We only want to send the profiler toolbar if the request is not an AJAX
		// request, as sending it on AJAX requests could mess up JSON driven API
		// type applications, so we will not send anything in those scenarios.
		if ( ! Request::ajax())
		{
			return render('path: '.__DIR__.'/template'.BLADE_EXT, static::$data);
		}
	}

	/**
	 * Add a log entry to the log entries array.
	 *
	 * @return void
	 */
	public static function api($code, $method, $url, $response)
	{
		static::$data['api_calls'][] = array($code, $method, $url, $response);
	}

	/**
	 * Detach the Profiler's event
	 * 
	 * @return void
	 */
	public static function detach()
	{
		// Clearing laravel.done Event to "detach" the profiler in case the API is ran on the same install
		Event::clear('laravel.done');
	}

	/**
	 * Attach the Profiler's event listeners.
	 *
	 * @return void
	 */
	public static function attach()
	{
		// First we'll attach to the query and log events. These allow us to catch
		// all of the SQL queries and log messages that come through Laravel,
		// and we will pass them onto the Profiler for simple storage.
		Event::listen('laravel.log', function($type, $message)
		{
			Profiler::log($type, $message);
		});

		Event::listen('laravel.query', function($sql, $bindings, $time)
		{
			Profiler::query($sql, $bindings, $time);			
		});

		// We'll attach the profiler to the "done" event so that we can easily
		// attach the profiler output to the end of the output sent to the
		// browser. This will display the profiler's nice toolbar.
		Event::listen('laravel.done', function($response)
		{
			echo Profiler::render($response);
		});
	}
}