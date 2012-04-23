<?php

use ShopHub\API;
use Laravel\Messages;

class Account_Backend_Accounts_Controller extends Shophub_Base_Controller {

	public $meta_title = 'Accounts';

	public $per_page = 10;

	public function get_index()
	{
		if(Authority::cannot('read', 'Account'))
		{
			return Redirect::to('auth/login');
		}

		$options = array();

		if(Input::has('q'))
		{
			$options['search'] = array(
				'string' => Input::get('q'),
				'columns' => array(
					'name', 
					'email'
				) 
			);
		}

		$options = array_merge($options, array(
			'offset' => (Input::get('page', 1) - 1) * $this->per_page,
			'limit' => $this->per_page,
			'sort_by' => Input::get('sort_by', 'name'),
			'order' => Input::get('order', 'ASC')
		));

		$total = API::get(array('account', 'total'), $options);

		$accounts = API::get(array('account', 'all'), $options);

		$accounts = Paginator::make($accounts, $total, $this->per_page);

		$this->layout->content = View::make('account::backend.accounts.index')->with('accounts', $accounts);
	}

	public function get_add()
	{
		if(Authority::cannot('create', 'Account'))
		{
			return Redirect::to('backend/accounts');
		}

		$roles = array('' => '') + array_pluck(API::get(array('role', 'all')), function($role) { 
			return $role->lang->name;
		}, 'uuid');

		$languages = array_pluck(API::get(array('language', 'all')), function($language) {
			return $language->name;
		}, 'uuid');

		$this->layout->content = View::make('account::backend.accounts.add')
									 ->with('roles', $roles)
									 ->with('languages', $languages);
	}

	public function post_add()
	{
		$response = API::post(array('account'), Input::all());

		if( ! is_object($response))
		{
			Notification::success('Successfully created account');

			return Redirect::to('backend/accounts');
		}

		if($response->code == 400)
		{
			return Redirect::to('backend/accounts/add')
						 ->with('errors', new Messages($response->errors))
				   ->with_input('except', array('password'));
		}

		return Event::first($response->code);
	}

	public function get_edit($uuid = null)
	{
		$account = API::get(array('account', $uuid));

		if(is_null($account) OR Authority::cannot('update', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$roles = array('' => '') + array_pluck(API::get(array('role', 'all')), function($role) {
			return $role->lang->name;
		}, 'uuid');

		$active_roles = array_pluck($account->roles, 'uuid', '');

		$languages = array_pluck(API::get(array('language', 'all')), function($language) {
			return $language->name;
		}, 'uuid');

		$this->layout->content = View::make('account::backend.accounts.edit')
									 ->with('account', $account)
									 ->with('roles', $roles)
									 ->with('active_roles', $active_roles)
 									 ->with('languages', $languages);
	}

	public function put_edit($uuid = null)
	{
		$response = API::put(array('account', $uuid), Input::all());

		if( ! is_object($response))
		{
			Notification::success('Successfully updated account');

			return Redirect::to('backend/accounts');
		}

		if($response->code == 400)
		{
			return Redirect::to('backend/accounts/edit/' . $uuid)
						 ->with('errors', new Messages($response->errors))
				   ->with_input('except', array('password'));
		}

		return Event::first($response->code);
	}

	public function get_delete($uuid = null)
	{
		$account = API::get(array('account', $uuid));

		if(is_null($account) OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$this->layout->content = View::make('account::backend.accounts.delete')
									 ->with('account', $account);
	}

	public function put_delete($uuid = null)
	{
		$response = API::delete(array('account', $uuid));

		if( ! is_object($response))
		{
			Notification::success('Successfully deleted account');
		}

		return Event::first($response->code);
	}

}