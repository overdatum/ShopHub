<?php namespace History;

use Laravel\Config;

class Message {
	
	/**
	 * All of the active cache drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	/**
	 * Get the message driver instance.
	 *
	 * If no driver name is specified, the default will be returned.
	 *
	 * <code>
	 *		// Get the default message driver instance
	 *		$driver = Message::driver();
	 *
	 *		// Get a specific message driver instance by name
	 *		$driver = Message::driver('memcached');
	 * </code>
	 *
	 * @param  string        $driver
	 * @return Message\Drivers\Driver
	 */
	public static function driver($driver = null)
	{
		if (is_null($driver)) $driver = Config::get('history::message.driver');

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
	 * @return Message\Drivers\Driver
	 */
	protected static function factory($driver)
	{
		if( ! $driver) $drive = Config::get('history::message.driver');

		switch ($driver)
		{
			case 'memory':
				return new Message\Drivers\Memory;

			default:
				throw new \Exception("Message driver {$driver} is not supported.");
		}
	}

	/**
	 * Magic Method for calling the methods on the default message driver.
	 *
	 * <code>
	 *		// Call the "subscribe" method on the default message driver
	 *		Message::subscribe('mychannel', function($message)
	 *		{
	 *			echo $message;
	 *		});
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}

}