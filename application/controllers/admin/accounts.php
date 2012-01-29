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

		$this->layout = View::make('layouts.default')->with('header_data', $header_data)->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_index()
	{
		if(Authority::cannot('read', 'Account'))
		{
			return Redirect::to('home');
		}

		$this->layout->content = View::make('admin.accounts.index')->with('accounts', Account::with('roles')->get());
	}

	public function get_add()
	{
		if(Authority::cannot('create', 'Account'))
		{
			return Redirect::to('admin/accounts/index');
		}

		$this->layout->content = View::make('admin.accounts.add');
	}

	public function post_add()
	{
		$account = new Account;

		$errors = $account->validate_and_insert();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('admin/accounts/add')->with('errors', $errors)->with_input('except', array('password'));
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

		$this->layout->content = View::make('admin.accounts.edit')->with('account', $account);
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
			return Redirect::to('admin/accounts/edit')->with('errors', $errors)->with_input('except', array('password'));
		}

		Notification::success('Successfully updated account');

		return Redirect::to('admin/accounts/index');
	}

	public function get_delete($id = 0)
	{
		$account = Account::find($id);

		var_dump(Authority::can('delete', 'Account', $account));
		die();

		if( ! $account OR $id == 0 OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('admin/accounts/index');
		}

		$this->layout->content = View::make('admin.accounts.delete');
	}

	public function get_delete_success() {
		$this->layout->content = View::make('admin.accounts.delete_success');
	}

	public function put_delete($id = 0)
	{
		$account = Account::find($id);
		if( ! $account OR $id == 0 OR Authority::cannot('delete', 'Account', $account))
		{
			return Redirect::to('admin/accounts/index');
		}

		$account->delete();
		return Redirect::to('admin/accounts/delete_success');
	}
}