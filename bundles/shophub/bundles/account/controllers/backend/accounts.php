<?php

class Account_Backend_Accounts_Controller extends Shophub_Base_Controller {

	public $restful = true;

	public $layout = true;

	public $meta_title = 'Accounts';

	public function get_index()
	{
		if(Authority::cannot('read', 'Account'))
		{
			return Redirect::to('home');
		}

		$accounts = Account::with('roles')->order_by(Input::get('sort_by', 'accounts.name'), Input::get('order', 'ASC'));

		if(Input::has('q'))
		{
			foreach(array('name', 'email') as $column)
			{
				$accounts->or_where($column, '~*', Input::get('q'));
			}
		}

		$this->layout->content = View::make('account::backend.accounts.index')->with('accounts', $accounts->paginate(10));

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

		$roles = $active_roles = array();

		foreach(Role::all() as $role)
		{
			$roles[$role->id] = $role->lang->name;
		}

		foreach(Account::find($id)->with('roles')->get() as $active_role)
		{
			$active_roles[] = $active_role->id;
		}

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

		$errors = $account->validate_and_update();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('backend/accounts/edit')
						 ->with('errors', $errors)
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

		Notification::success('Successfully deleted account');

		return Redirect::to('backend/accounts');
	}

}