<?php
class Admin_Accounts_Controller extends Controller {

	public $restful = true;
	public $layout = true;

	public function __construct()
	{
		$this->filter('before', 'auth|is_admin');
	}

	public function layout()
	{
		$menu_data = array(
			'menu' => Config::get('menus.admin')
		);

		$header_data = array(
			'title' => 'Admin | Accounts'
		);

		$this->layout = View::make('layouts.default')
							->with('header_data', $header_data)
							->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_index()
	{
		if(Authority::cannot('read', 'Account'))
		{
			return Redirect::to('home');
		}

		$roles_lang = array();
		foreach(DB::table('role_lang')->get() as $role_lang)
		{
			$roles_lang[$role_lang->id] = $role_lang;
		}

		$accounts = Account::with('roles')->order_by(Input::get('sort_by', 'accounts.name'), Input::get('order', 'ASC'));
		if(Input::has('q'))
		{
			foreach(array('name', 'email') as $column)
			{
				$accounts->or_where($column, '~*', Input::get('q'));
			}
		}
		$this->layout->content = View::make('admin.accounts.index')
									 ->with('accounts', $accounts->paginate(10))
									 ->with('roles_lang', $roles_lang);
	}

	public function get_add()
	{
		if(Authority::cannot('create', 'Account'))
		{
			return Redirect::to('admin/accounts/index');
		}

		$roles_lang = array();
		foreach(DB::table('role_lang')->get() as $role_lang)
		{
			$roles_lang[$role_lang->id] = $role_lang;
		}

		$roles = array();
		foreach(Role::all() as $role)
		{
			$roles[$role->id] = $roles_lang[$role->id]->name;
		}

		$this->layout->content = View::make('admin.accounts.add')
									 ->with('roles', $roles);
	}

	public function post_add()
	{
		$account = new Account;

		$errors = $account->validate_and_insert();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('admin/accounts/add')
						 ->with('errors', $errors)
				   ->with_input('except', array('password'));
		}

		Notification::success('Successfully created account');

		return Redirect::to('admin/accounts/index');
	}

	public function get_edit($id = 0)
	{
		$account = Account::find($id);

		if( ! $account OR $id == 0 OR Authority::cannot('update', 'Account', $account))
		{
			return Redirect::to('admin/accounts/index');
		}

		$roles_lang = array();
		foreach(DB::table('role_lang')->get() as $role_lang)
		{
			$roles_lang[$role_lang->id] = $role_lang;
		}

		$roles = array();
		foreach(Role::all() as $role)
		{
			$roles[$role->id] = $roles_lang[$role->id]->name;
		}

		$active_roles = array();
		foreach(DB::table('accounts_roles')->where_account_id($id)->get(array('role_id')) as $role)
		{
			$active_roles[] = $role->role_id;
		}

		$this->layout->content = View::make('admin.accounts.edit')
									 ->with('account', $account)
									 ->with('roles', $roles)
									 ->with('active_roles', $active_roles);
	}

	public function put_edit($id = 0)
	{
		$account = Account::find($id);
		if( ! $account OR $id == 0)
		{
			return Redirect::to('admin/accounts/index');
		}

		$errors = $account->validate_and_update();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('admin/accounts/edit')
						 ->with('errors', $errors)
				   ->with_input('except', array('password'));
		}

		Notification::success('Successfully updated account');

		return Redirect::to('admin/accounts/index');
	}

	public function get_delete($id = 0)
	{
		$account = Account::find($id);

		if( ! $account OR $id == 0 OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('admin/accounts/index');
		}

		$this->layout->content = View::make('admin.accounts.delete')
									 ->with('account', $account);
	}

	public function put_delete($id = 0)
	{
		$account = Account::find($id);
		if( ! $account OR $id == 0 OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('admin/accounts/index');
		}

		$account->delete();

		Notification::success('Successfully deleted account');

		return Redirect::to('admin/accounts/index');
	}
}