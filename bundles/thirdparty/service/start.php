<?php

Autoloader::map(array(
	'Service' => __DIR__ . DS . 'service' . EXT
));

/**
 * Register a JSON service
 * 
 * @param  Service $service
 * @return string
 */
Service::register('json', function(Service $service)
{
	$service->header('Content-Type', 'application/json');

	if( ! isset($service->data))
	{
		return '';
	}

	if(Input::get('pretty'))
	{
		return prettify_json(json_encode($service->data));
	}
	
	return json_encode($service->data);
});