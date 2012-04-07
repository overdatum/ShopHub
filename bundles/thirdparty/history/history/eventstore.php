<?php namespace History;

use Exception;
use Laravel\Config;

class EventStore {

	/**
	 * All of the active EventStore drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	/**
	 * Get the EventStore driver instance.
	 *
	 * If no driver name is specified, the default will be returned.
	 *
	 * <code>
	 *		// Get the default message driver instance
	 *		$driver = EventStore::driver();
	 *
	 *		// Get a specific message driver instance by name
	 *		$driver = EventStore::driver('memory');
	 * </code>
	 *
	 * @param  string        $driver
	 * @return Stores\EventStore\Drivers\Driver
	 */
	public static function driver($driver = null)
	{
		if (is_null($driver)) $driver = Config::get('history::eventstore.driver');

		if ( ! isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new message driver instance.
	 *
	 * @param  string  $driver
	 * @return Stores\EventStore\Drivers\Driver
	 */
	protected static function factory($driver)
	{
		if( ! $driver) $drive = Config::get('history::eventstore.driver');

		switch ($driver)
		{
			case 'memory':
				return new EventStore\Drivers\Memory;

			case 'pdo':
				return new EventStore\Drivers\PDO;

			default:
				throw new Exception("EventStore driver {$driver} is not supported.");
		}
	}

	/**
	 * Magic Method for calling the methods on the default EventStore driver.
	 *
	 * <code>
	 *		// Get all events for an Entity by it's UUID
	 *		EventStore::get_all_events($uuid)
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}

}