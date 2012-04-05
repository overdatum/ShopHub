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

		$roles = array();
		foreach(Role::all() as $role)
		{
			$roles[$role->id] = $role->lang->name;
		}
		
		$this->layout->content = View::make('account::backend.accounts.add')
									 ->with('roles', $roles);
	}

	public function post_add()
	{
		$account = new Account;
		$account->fill(Input::all());

		if( ! $account->save())
		{
			return Redirect::to('admin/accounts/add')
						 ->with('errors', $account->errors)
				   ->with_input('except', array('password'));
		}
		
		var_dump($account->events); die;

		Notification::success('Successfully created account');

		return Redirect::to('backend/accounts');
	}

	public function get_edit($id = 0)
	{
		$account = Account::find($id);

		if( ! $account OR $id == 0 OR Authority::cannot('update', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$roles = array('') + array_pluck(Role::all(), function($role) { return $role->lang->name; });

		$active_roles = array_pluck(Account::find($id)->with('roles')->roles()->get(), 'id', '');

		$this->layout->content = View::make('account::backend.accounts.edit')
									 ->with('account', $account)
									 ->with('roles', $roles)
									 ->with('active_roles', $active_roles);
	}

	public function put_edit($id = 0)
	{
		$account = Account::find($id);

		if( ! $account OR $id == 0)
		{
			return Redirect::to('backend/accounts');
		}

		if(Input::get('password') !== '') Account::$accessible[] = 'password';

		$account->fill(Input::all());
		$account->roles()->sync(Input::get('role_ids'), '0');
		
		if( ! $account->save())
		{
			return Redirect::to('backend/accounts/edit')
						 ->with('errors', $account->errors->all())
				   ->with_input('except', array('password'));			
		}

		Notification::success('Successfully updated account');

		return Redirect::to('backend/accounts');
	}

	public function get_delete($id = 0)
	{
		$account = Account::find($id);

		if( ! $account OR $id == 0 OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$this->layout->content = View::make('account::backend.accounts.delete')
									 ->with('account', $account);
	}

	public function put_delete($id = 0)
	{
		$account = Account::find($id);
		if(is_null($account) OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('backend/accounts');
		}

		$account->delete();

		var_dump($account->event); die;

		Notification::success('Successfully deleted account');

		return Redirect::to('backend/accounts');
	}

}