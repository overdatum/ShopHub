<?php

/**
 * The Service class simplifies web service routing.
 * 
 * @package   Service
 * @category  Bundle
 * @author    Phill Sparks <me@phills.me.uk>
 * @copyright 2012 Phill Sparks
 * @license   MIT License <http://www.opensource.org/licenses/mit>
 * 
 * <code>
 * 	// Method A
 * 	Service::get('user/(:any)', array('html', 'json', 'xml'), function(Service $service, $slug)
 * 	{
 * 		$service->data['user'] = User::where_slug($slug);
 * 		
 * 		// Handle HTML type
 * 		if ($service->type == 'html')
 * 		{
 * 			return View::make('user.show', array(
 * 				'user' => $service->data['user']
 * 			));
 * 		}
 * 	});
 * 	
 * 	// Method B
 * 	Route::get(array('user/(:any)', 'user/(:any).(json|xml)'), function($slug, $type = 'html')
 * 	{
 * 		return Service::respond($type, array('html', 'json', 'xml'), function(Service $service) use ($slug)
 * 		{
 * 			$service->data['user'] = User::where_slug($slug);
 * 
 * 			// Handle HTML type
 *  		if ($service->type == 'html')
 * 			{
 * 				return View::make('user.show', array(
 * 					'user' => $service->data['user']
 * 				));
 * 			}
 * 		});
 * 	});
 * </code>
 */

class Service extends Response
{

	/**
	 * @var array
	 */
	public $data = array();

	/**
	 * @var string
	 */
	public $type;

	/**
	 * Create a new Service
	 * 
	 * Public code should call respond.
	 * 
	 * @param  string $type
	 * @return void
	 */
	public function __construct($type)
	{
		// Content is initially blank
		parent::__construct('');

		$this->type = $type;
	}
	
	/**
	 * Respond to a service request.
	 * 
	 * @param  string  $type
	 * @param  array   $allowed
	 * @param  Closure $callback
	 * @param  array   $args
	 * @return mixed
	 */
	public static function respond($type, $allowed, Closure $callback, $args = array())
	{
		if (is_null($type))
		{
			// Although we pass in the allowed list this does not guarantee
			// that the returned type will be one of $allowed.
			$type = Service::detect($allowed);
		}

		if (in_array($type, $allowed))
		{
			$service = new Service($type);

			// If the callback returns something then we'll return it ourselves
			if ($response = call_user_func_array($callback, array_merge(array($service), $args)))
			{
				return $response;
			}

			// If we have a handler then call it and use the return value as
			// the content for this response.
			if ($handler = static::resolve($type))
			{
				$service->content = $handler($service);
				return $service;
			}
			// If there is no handler then this is considered a 404 error,
			// despite the service being allowed.
		}
		return Event::first('404'); // or other 4xx
	}

	/**
	 * @var array
	 */
	protected static $services = array();

	/**
	 * Register a new service.
	 * 
	 * @param  string $type
	 * @param  Closure $callback
	 * @return void
	 */
	public static function register($type, Closure $callback)
	{
		static::$services[$type] = $callback;
	}

	/**
	 * Return a registered service.
	 * 
	 * @param  string $type
	 * @return Closure
	 */
	public static function resolve($type)
	{
		if (array_key_exists($type, static::$services))
		{
			return static::$services[$type];
		}
	}

	/**
	 * Detect the type of the request
	 * 
	 * @return string
	 * @todo parse request headers
	 */
	public static function detect($services)
	{
		/* check for .{type} */
		if ($type = File::extension(URI::current()))
		{
			return $type;
		}
		/* check for ?format={type} */
		else if ($type = Input::get('format'))
		{
			return $type;
		}
		/* check for ?{type}=1 */
		else foreach ($services as $type)
		{
			if (Input::get($type))
			{
				return $type;
			}
		}
		/* parse request headers <http://www.xml.com/pub/a/2005/06/08/restful.html> */

		/* finally, use the first service as the default */
		return reset($services);
	}

	/**
	 * Register a service route with the Router
	 * 
	 * @param  string $method
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	protected static function route($method, $route, $services, $action)
	{
		// Modify the route to accept the $type
		$route = array(
			$route, $route.'\.(?:'.implode('|', $services).')',
		);

		// Find the callback method
		if (is_array($action))
		{
			foreach ($action as $key => $value)
			{
				if ($value instanceof Closure)
				{
					$callback = $value;
					unset($action[$key]);
				}
			}
		}
		else
		{
			$callback = $action;
			$action = array();
		}

		// Wrap the action in some service magic
		$action[] = function(/* ... */) use ($services, $callback)
		{
			$args = func_get_args();
			$type = Service::detect($services);
			return Service::respond($type, $services, $callback, $args);
		};

		// Register the service route with the Router
		Router::register($method, $route, $action);
	}

	/**
	 * Mimic Route::get
	 * 
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	public static function get($route, $services, $action)
	{
		static::route('GET', $route, $services, $action);
	}

	/**
	 * Mimic Route::post
	 * 
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	public static function post($route, $services, $action)
	{
		static::route('POST', $route, $services, $action);
	}

	/**
	 * Mimic Route::put
	 * 
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	public static function put($route, $services, $action)
	{
		static::route('PUT', $route, $services, $action);
	}

	/**
	 * Mimic Route::delete
	 * 
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	public static function delete($route, $services, $action)
	{
		static::route('DELETE', $route, $services, $action);
	}

	/**
	 * Mimic Route::any
	 * 
	 * @param  string $route
	 * @param  array  $services
	 * @param  mixed  $action
	 * @return void
	 */
	public static function any($route, $services, $action)
	{
		static::route('*', $route, $services, $action);
	}

}
