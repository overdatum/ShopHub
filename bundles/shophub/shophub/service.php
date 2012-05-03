<?php namespace ShopHub;

use ShopHub\Profiling\Profiler;

use Closure;
use Laravel\Event;
use Laravel\Response;
use Laravel\Routing\Router;

use Service as Bundle_Service;

class Service extends Bundle_Service {

	public static $group = array();

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
	public static function respond($type, $allowed, Closure $callback, $args = array())
	{
		Profiler::detach();

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

	protected static function route($method, $route, $services, $action)
	{
		$prefix = 'api/';
		$prefix .= array_key_exists('prefix', static::$group) ? static::$group['prefix'] . '/' : '';

		// Modify the route to accept the $type
		$route = array(
			$prefix . $route,
			$prefix . $route.'\.(?:'.implode('|', $services).')',
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

	public static function group($attributes, $callback)
	{
		// Service groups allow the developer to specify attributes for a service
		// of services. To register them, we'll set a static property on the
		// service so that the register method will see them.
		static::$group = $attributes;

		call_user_func($callback);

		// Once the services have been registered, we want to set the group to
		// null so the attributes will not be given to any of the services
		// that are added after the group is declared.
		static::$group = null;
	}

}