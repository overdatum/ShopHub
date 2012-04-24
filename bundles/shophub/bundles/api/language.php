<?php namespace API;

use Laravel\Database as DB;
use Laravel\Input;
use Service;

use Language;

Service::post('api/language', array('json', 'xml'), function(Service $service)
{
	// Create a new Language object	
	$language = new Language(Input::all());

	// Try to save
	if( ! $language->save())
	{
		// Return 400 response with errors
		$service->status(400);
		$service->data = (array) $language->errors->messages;

		return $service;
	}

	// Return the language's uuid
	$service->data = $language->get_key();
});

Service::get('api/language/all', array('json', 'xml'), function(Service $service)
{
	// Overriding default options with the "user-set" ones
	$options = array_merge(array(
		'offset' => 0,
		'limit' => 20,
		'sort_by' => 'languages.name',
		'order' => 'ASC'
	), Input::all());

	// Add tablename prefix to sort_by, if set
	if(Input::has('sort_by'))
	{
		$options['sort_by'] = 'languages.' . Input::get('sort_by');
	}

	// Preparing our query
	$languages = new Language;

	// Add where's to our query
	if(array_key_exists('search', $options))
	{
		foreach($options['search']['columns'] as $column)
		{
			$languages = $languages->or_where($column, '~*', $options['search']['string']);
		}
	}

	// Add order_by, skip & take to our results query
	$languages = $languages->order_by($options['sort_by'], $options['order'])->skip($options['offset'])->take($options['limit']);

	// Calling to_array on every account model
	$languages = array_map(function($languages) {
		return $languages->to_array();
	}, $languages->get());

	// Returning the data
	$service->data = $languages;
});

Service::get('api/language/total', array('json', 'xml'), function(Service $service)
{
	// Grabbing the user-defined options
	$options = Input::all();

	// Preparing our query
	$total = new Language;

	// Add where's to our query, if needed
	if(array_key_exists('search', $options))
	{
		foreach($options['search']['columns'] as $column)
		{
			$total = $total->or_where($column, '~*', $options['search']['string']);
		}
	}

	// Getting the total of accounts
	$total = (int) $total->count();

	// Returning the data
	$service->data = $total;
});

Service::get('api/language/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	$service->data = Language::find($uuid)->to_array();
});