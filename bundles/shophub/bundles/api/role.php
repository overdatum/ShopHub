<?php namespace API;

use Laravel\Database as DB;
use Laravel\Input;
use Service;

use Role;

Service::post('api/role', array('json', 'xml'), function(Service $service)
{
	$role = new Role(Input::all());
	$role->save();
	$service->data = $role->get_key();
});

Service::get('api/role/all', array('json', 'xml'), function(Service $service)
{
	// Overriding default options with the "user-set" ones
	$options = array_merge(array(
		'offset' => 0,
		'limit' => 20,
		'sort_by' => 'roles.name',
		'order' => 'ASC'
	), Input::all());

	// Add tablename prefix to sort_by, if set
	if(Input::has('sort_by'))
	{
		$options['sort_by'] = 'roles.' . Input::get('sort_by');
	}

	// Preparing our query
	$roles = Role::with(array('lang'));

	// Add where's to our query
	if(array_key_exists('search', $options))
	{
		foreach($options['search']['columns'] as $column)
		{
			$roles = $roles->or_where($column, '~*', $options['search']['string']);
		}
	}

	// Add order_by, skip & take to our results query
	$roles = $roles->order_by($options['sort_by'], $options['order'])->skip($options['offset'])->take($options['limit']);

	// Calling to_array on every account model
	$roles = array_map(function($roles) {
		return $roles->to_array();
	}, $roles->get());

	// Returning the data
	$service->data = $roles;
});

Service::get('api/roles/total', array('json', 'xml'), function(Service $service)
{
	// Grabbing the user-defined options
	$options = Input::all();

	// Preparing our query
	$total = new Role;

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

Service::get('api/role/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	$service->data = Role::find($uuid)->to_array();
});