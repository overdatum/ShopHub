<?php

class Service extends Response {

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

	public function response($code, $data)
	{
		return Response::make(json_encode($data), $code);
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
	public static function respond($type, $allowed, $action, $args = array())
	{
		Profiler::detach();

		if (in_array($type, $allowed))
		{
			$service = new Service($type);

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
		$is_versioned = is_string($action) && strpos($action, '(:version)') > 0;

		// Modify the route to accept the version
		if($is_versioned)
		{
			$route = '(:any)/' . $route;
		}

		// Modify the route to accept the $type
		$route = array(
			$route, $route.'\.(?:'.implode('|', $services).')',
		);

		// Wrap the action in some service magic
		$action = function() use ($services, $action, $is_versioned)
		{
			$args = func_get_args();

			if($is_versioned)
			{
				$action = str_replace('(:version)', array_shift($args), $action);
			}

			$type = Service::detect($services);
			return Service::respond($type, $services, $action, $args);
		};

		// Register the service route with the Router
		Router::register($method, $route, $action);
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

/**
 * Register a JSON service
 * 
 * @param  Service $service
 * @return string
 */
Service::register('json', function(Service $service)
{
	$service->header('Content-Type', 'application/json');

	if(Input::get('pretty'))
	{
		return prettify_json(json_encode($service->data));
	}
	
	return json_encode($service->data);
});

IoC::register('service', function()
{
	return new Service;
});

Service::get('api/testing/(:any)', array('xml', 'json'), 'shophub_api::(:version).account@test');