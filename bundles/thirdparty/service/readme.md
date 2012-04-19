#Service Bundle

Simplified service routing

##Install with Artisan

	php artisan bundle:install service

##Bundle Registration

	'service' => array(
		'auto' => true, // Load some default services
		'autoloads' => array(
			'map' => array(
				'Service' => '(:bundle)/service.php',
			),
		),
	),

##Method A

	Service::get('user/(:any)', array('html', 'json', 'xml'), function(Service $service, $slug)
	{
		$service->data['user'] = User::where_slug($slug);
		
		// Handle HTML type
		if ($service->type == 'html')
		{
			return View::make('user.show', array(
				'user' => $service->data['user']
			));
		}
	});

##Method B

	Route::get(array('user/(:any)', 'user/(:any).(json|xml)'), function($slug, $type = 'html')
	{
		return Service::respond($type, array('html', 'json', 'xml'), function(Service $service) use ($slug)
		{
			$service->data['user'] = User::where_slug($slug);

			// Handle HTML type
			if ($service->type == 'html')
			{
				return View::make('user.show', array(
					'user' => $service->data['user']
				));
			}
		});
	});