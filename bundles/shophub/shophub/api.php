<?php namespace Shophub;

use Exception;

use Laravel\Config;
use Laravel\Routing\Route;
use Laravel\Response;

use API\Drivers\Memory;
use API\Drivers\HTTP;

class API {

	/**
	 * All of the active API drivers.
	 *
	 * @var array
	 */
	public static $drivers = array();

	public static function serve($prefix = 'api')
	{
		Route::any($prefix . '/(:any?)/(:any?)/(:all?)', function($alias, $method, $arguments = '')
		{
			$repositories = Config::get('shophub::api.repositories');
			$class = new $repositories[$alias];
			$arguments = explode('/', $arguments);

			return Response::make(json_encode(call_user_func_array(array($class, $method), $arguments)), 200, array('content-type: application/json'));
		});
	}

	/**
	 * Get a API driver instance.
	 *
	 * If no driver name is specified, the default will be returned.
	 *
	 * <code>
	 *		// Get the default cache driver instance
	 *		$driver = API::driver();
	 *
	 *		// Get a specific cache driver instance by name
	 *		$driver = API::driver('http');
	 * </code>
	 *
	 * @param  string        $driver
	 * @return API\Drivers\Driver
	 */
	public static function driver($driver = '')
	{
		if (is_null($driver)) $driver = Config::get('shophub::api.driver');

		if ( ! isset(static::$drivers[$driver]))
		{
			static::$drivers[$driver] = static::factory($driver);
		}

		return static::$drivers[$driver];
	}

	/**
	 * Create a new cache driver instance.
	 *
	 * @param  string  $driver
	 * @return Cache\Drivers\Driver
	 */
	protected static function factory($driver)
	{
		switch ($driver)
		{
			case 'memory':
				return new API\Drivers\Memory;
			break;

			case 'http':
				return new API\Drivers\HTTP;
			break;

			default:
				throw new Exception("API driver {$driver} is not supported.");
			break;
		}
	}

	/**
	 * Magic Method for calling the methods on the default API driver.
	 *
	 * <code>
	 *		// Call the "call" method on the default API driver
	 *		$name = API::call(array('users', 'all'));
	 * </code>
	 */
	public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::driver(), $method), $parameters);
	}

}