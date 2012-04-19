<?php namespace API;

use Laravel\Database as DB;
use Service;

use Account;

Service::post('account', array('json', 'xml'), function(Service $service)
{
	$account = new Account(Input::all());
	$uuid = $account->save();
	
	$service->data = $uuid;
});

Service::get('account/all', array('json', 'xml'), function(Service $service)
{
	$accounts = Account::with('roles')->get();
	$results = array();
	$language_uuids = array();
	foreach ($accounts as $account)
	{
		$language_uuids[] = $account->language_uuid;
		foreach ($account->roles as $role) {
			$account->attributes['roles'][] = $role->attributes;
		}
		$results[] = $account->attributes;
	}
	
	$languages = array_pluck(DB::table('languages')->where_in('uuid', $language_uuids)->get(), function($language)
	{
		return (array) $language;
	}, 'uuid');

	foreach ($results as &$result)
	{
		$result['language'] = $languages[$account->language_uuid];
		unset($result['password']);
		unset($result['language_uuid']);
	}

	$service->data = $results;
});

Service::get('account/(:any)', array('json', 'xml'), function(Service $service, $uuid)
{
	$account = DB::table('accounts')->where_uuid($uuid)->first();
	$language = DB::table('languages')->where_uuid($account->language_uuid)->first();
	$account->language = $language;
	unset($account->password);
	unset($account->language_uuid);
	
	$service->data = $account;
});