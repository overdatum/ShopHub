<?php namespace API;

use Laravel\Event;
use Laravel\Response;
use Laravel\Database as DB;
use Laravel\Input;
use Service;

use Account;
use Role;

Service::post('api/account', array('json', 'xml'), function(Service $service)
{
	// We are adding an account, password is accessible and required
	Account::$rules['password'] = 'required';
	Account::$rules['email'] .= '|unique:accounts,email';
	Account::$accessible[] = 'password';

	// Create a new Account object
	$account = new Account(Input::all());

	// Sync some roles
	$account->roles()->sync(Input::get('role_uuids'), '');

	// Try to save
	if( ! $account->save())
	{
		// Return 400 response with errors
		$service->status(400);
		$service->data = (array) $account->errors->messages;
	}
	else
	{
		// Return the account's uuid
		$service->data = $account->get_key();
	}
});

Service::put('api/account/(:any)', array('json', 'xml'), function(Service $service, $uuid = null)
{
	if( ! is_uuid($uuid))
	{
		return $service->status(400);
	}

	// Find the account we are updating
	$account = Account::find($uuid);

	if(is_null($account))
	{
		// Resource not found, return 404
		return $service->status(404);
	}

	/* TODO
	Authority::cannot('update', 'Account', $account))
	{
		return Event::first(401);
	}*/

	// If the password is set, we allow it to be updated
	if(Input::get('password') !== '') Account::$accessible[] = 'password';

	// Fill the account with the PUT data
	$account->fill(Input::all());

	// Sync the roles (attach & detach the appropiate ones)
	$account->roles()->sync(Input::get('role_uuids'), '');

	// Try to save
	if( ! $account->save())
	{
		// Return 400 response with errors
		$service->status(400);
		$service->data = (array) $account->errors->messages;
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
	$accounts = Account::with(array('roles', 'roles.lang', 'language'));

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
	if( ! is_uuid($uuid))
	{
		return $service->status(400);
	}

	// Get the Account
	$account = Account::with(array('roles', 'language'))->where_uuid($uuid)->first();
	
	if(is_null($account))
	{
		// Resource not found, return 404
		return $service->status(404);
	}

	$service->data = $account->to_array();
});

Service::delete('api/account/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	// Find the account we are updating
	$account = Account::find($uuid);

	if(is_null($account))
	{
		// Resource not found, return 404
		return $service->status(404);
	}

	$account->delete();
});