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
	if(false)
	{
		return "<script>var test = " . json_encode($service->data) . "; console.log(test);</script>";
	}
	
	$service->header('Content-Type', 'application/json');

	return json_encode($service->data);
});