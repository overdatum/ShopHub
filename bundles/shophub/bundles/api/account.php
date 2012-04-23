<?php namespace API;

use Laravel\Response;
use Laravel\Database as DB;
use Laravel\Input;
use Service;

use Account;
use Role;

Service::post('api/account', array('json', 'xml'), function(Service $service)
{
	Account::$rules['password'] = 'required';
	Account::$accessible[] = 'password';

	// Create a new Account object	
	$account = new Account(Input::all());

	// Synq some roles
	$account->roles()->sync(Input::get('role_ids'), '');

	// Try to save
	if( ! $account->save())
	{
		// Return 400 response with errors
		$errors = (array) $account->errors->messages;
		return Response::make(json_encode($errors), 400, array('content-type', 'application/json'));
	}
	else
	{
		// Return the account's uuid
		$service->data = $account->get_key();
	}
});

Service::put('api/account/(:any)', array('json', 'xml'), function(Service $service, $uuid = null)
{
	$account = Account::find($uuid);

	/* TODO
	Authority::cannot('update', 'Account', $account))
	{
		return Event::first(401);
	}*/

	if(is_null($account))
	{
		return Event::first('404');
	}

	if(Input::get('password') !== '') Account::$accessible[] = 'password';

	$account->fill(Input::all());
	$account->roles()->sync(Input::get('role_ids'), '');

	if( ! $account->save())
	{
		$errors = (array) $account->errors->messages;
		return Response::make(json_encode($errors), 400, array('content-type', 'application/json'));
	}
});

Service::get('api/account/all', array('json', 'xml'), function(Service $service)
{
	// Overriding default options with the "user-set" ones
	$options = array_merge(array(
		'offset' => 0,
		'limit' => 20,
		'sort_by' => 'accounts.name',
		'order' => 'ASC'
	), Input::all());

	// Add tablename prefix to sort_by, if set
	if(Input::has('sort_by'))
	{
		$options['sort_by'] = 'accounts.' . Input::get('sort_by');
	}

	// Preparing our query
	$accounts = Account::with(array('roles', 'language'));

	// Add where's to our query
	if(array_key_exists('search', $options))
	{
		foreach($options['search']['columns'] as $column)
		{
			$accounts = $accounts->or_where($column, '~*', $options['search']['string']);
		}
	}

	// Add order_by, skip & take to our results query
	$accounts = $accounts->order_by($options['sort_by'], $options['order'])->skip($options['offset'])->take($options['limit']);

	// Calling to_array on every account model
	$accounts = array_map(function($account) {
		return $account->to_array();
	}, $accounts->get());

	// Returning the data
	$service->data = $accounts;
});

Service::get('api/account/total', array('json', 'xml'), function(Service $service)
{
	// Grabbing the user-defined options
	$options = Input::all();

	// Preparing our query
	$total = new Account;

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

Service::get('api/account/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	$service->data = Account::with(array('roles', 'language'))->where_uuid($uuid)->first()->to_array();
});