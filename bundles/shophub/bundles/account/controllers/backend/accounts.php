<?php

class Account_Backend_Accounts_Controller extends Shophub_Base_Controller {

	public $meta_title = 'Accounts';

	public function get_index()
	{
		if(Authority::cannot('read', 'Account'))
		{
			return Redirect::to('auth/login');
		}

		$accounts = Account::with('roles')->order_by(Input::get('sort_by', 'accounts.name'), Input::get('order', 'ASC'));

		if(Input::has('q'))
		{
			foreach(array('name', 'email') as $column)
			{
				$accounts->or_where($column, '~*', Input::get('q'));
			}
		}

		$this->layout->content = View::make('account::backend.accounts.index')->with('accounts', $accounts->paginate());

	}

	public function get_add()
	{
		if(Authority::cannot('create', 'Account'))
		{
			return Redirect::to('backend/accounts');
		}

		$roles = array_pluck(Role::all(), function($role) { return $role->lang->name; }, 'uuid');
		
		$languages = array_pluck(Language::all(), function($language) { return $language->name; }, 'uuid');

		$this->layout->content = View::make('account::backend.accounts.add')
									 ->with('roles', $roles)
									 ->with('languages', $languages);
	}

	public function post_add()
	{
		Account::$rules['password'] = 'required';
		Account::$accessible[] = 'password';
		
		$account = new Account;
		$account->fill(Input::all());

		$account->roles()->sync(Input::get('role_ids'), '');

		if( ! $account->save())
		{
			return Redirect::to('backend/accounts/add')
						 ->with('errors', $account->errors)
				   ->with_input('except', array('password'));
		}
		
		Notification::success('Successfully created account');

		return Redirect::to('backend/accounts');
	}

	public function get_edit($uuid = null)
	{
		$account = Account::find($uuid);

		if(is_null($account) OR is_null($uuid) OR Authority::cannot('update', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$roles = array('' => '') + array_pluck(Role::all(), function($role) { return $role->lang->name; }, 'uuid');

		$active_roles = array_pluck(Account::find($uuid)->with('roles')->roles()->get(), 'uuid', '');

		$languages = array_pluck(Language::all(), function($language) { return $language->name; }, 'uuid');

		$this->layout->content = View::make('account::backend.accounts.edit')
									 ->with('account', $account)
									 ->with('roles', $roles)
									 ->with('active_roles', $active_roles)
 									 ->with('languages', $languages);
	}

	public function put_edit($uuid = null)
	{
		$account = Account::find($uuid);

		if(is_null($account) OR Authority::cannot('update', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		if(Input::get('password') !== '') Account::$accessible[] = 'password';

		$account->fill(Input::all());
		$account->roles()->sync(Input::get('role_ids'), '');
		
		if( ! $account->save())
		{
			return Redirect::to('backend/accounts/edit/' . $uuid)
						 ->with('errors', $account->errors)
				   ->with_input('except', array('password'));
		}

		Notification::success('Successfully updated account');

		return Redirect::to('backend/accounts');
	}

	public function get_delete($uuid = null)
	{
		$account = Account::find($uuid);

		if(is_null($account) OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$this->layout->content = View::make('account::backend.accounts.delete')
									 ->with('account', $account);
	}

	public function put_delete($uuid = null)
	{
		$account = Account::find($uuid);
	
		if(is_null($account) OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$account->delete();

		Notification::success('Successfully deleted account');

		return Redirect::to('backend/accounts');
	}

}