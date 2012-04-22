<?php namespace API;

use Laravel\Database as DB;
use Laravel\Input;
use Service;

use Account;
use Role;

Service::post('api/account', array('json', 'xml'), function(Service $service)
{
	$account = new Account(Input::all());
	$account->save();
	$service->data = $account->get_key();
});

Service::get('api/account/all', array('json', 'xml'), function(Service $service)
{
	$options = array_merge(array(
		'offset' => 0,
		'limit' => 20
	), Input::all());

	$accounts = Account::with(array('roles', 'roles.lang', 'language'));

	if(array_key_exists('search', $options))
	{
		foreach($options['search']['columns'] as $column)
		{
			$accounts->or_where($column, '~*', $options['search']['string']);
		}
	}

	$accounts->skip($options['offset'])->take($options['limit']);

	$service->data = array_map(function($account) {
		return $account->to_array();
	}, $accounts->get());
});

Service::get('api/account/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	$service->data = Account::find($uuid)->to_array();
});