<?php

/**
 * Anbu, the light weight profiler for Laravel.
 *
 * @author Dayle Rees <me@daylerees.com>
 * @copyright 2012 Dayle Rees <me@daylerees.com>
 * @license MIT License <http://www.opensource.org/licenses/mit>
 */
class Anbu
{
	/**
	 * A list of attributes to watch.
	 *
	 * @var array
	 */
	private static $_watchlist = array();

	/**
	 * Contains the logs written by Laravel.
	 *
	 * @var array
	 */
	private static $_loglist = array();

	/**
	 * A list of SQL queries exectued.
	 *
	 * @var array
	 */
	private static $_sqllist = array();

	/**
	 * Show full resources instead of minified ones.
	 *
	 * @var bool
	 */
	private static $_debug = false;

	/**
	 * Render Anbu, assign view params and echo out the main view.
	 *
	 * @return void
	 */
	public static function render()
	{
		$data = array(
			'watch' => static::$_watchlist,
			'log'	=> static::$_loglist,
			'sql'	=> static::$_sqllist,
			'css'	=> File::get(Bundle::path('anbu').'public/css/style.min.css'),
			'js'	=> File::get(Bundle::path('anbu').'public/js/script.min.js'),
			'include_jq'	=> Config::get('anbu::display.include_jquery')
		);

		echo View::make('anbu::main', $data)->render();
	}

	/**
	 * Watch an object to show within Anbu
	 *
	 * @param string A friendly name for the object.
	 * @param mixed The object itself.
	 * @return void
	 */
	public static function watch($name, $object)
	{
		// pass the actual object in
		static::$_watchlist[$name] = $object;
	}

	/**
	 * Watch an object to show within Anbu, but keep
	 * watching its value after changes.
	 * (pass by reference)
	 *
	 * @param string A friendly name for the object.
	 * @param mixed The object itself.
	 * @return void
	 */
	public static function spy($name, &$object)
	{
		// pass the actual object in
		static::$_watchlist[$name] =& $object;
	}

	/**
	 * Add a log entry to the Anbu array.
	 *
	 * @param string The type of log entry.
	 * @param string The message.
	 * @return void
	 */
	public static function log($type, $message)
	{
		static::$_loglist[] = array($type, $message);
	}

	/**
	 * Add a performed SQL query to Anbu.
	 *
	 * @param string the SQL query performed.
	 * @param array The bindings for the query.
	 * @param float The time taken to run the query.
	 * @return void
	 */
	public static function sql($sql, $bindings, $time)
	{
		// I used this method to swap in the bindings, its very ugly
		// will be replaced later, hopefully will find something in
		// the core
		foreach ($bindings as $b)
		{
			$count = 1;
			$sql = str_replace('?', '`'.$b.'`', $sql,$count);
		}

		static::$_sqllist[] = array($sql, $time);
	}
}
