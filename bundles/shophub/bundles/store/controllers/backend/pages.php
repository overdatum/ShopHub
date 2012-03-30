<?php
class Admin_Stores_Controller extends Controller {

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
			'title' => 'Admin | Stores'
		);

		$this->layout = View::make('layouts.default')
							->with('header_data', $header_data)
							->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_index()
	{
		if(Authority::cannot('read', 'Store'))
		{
			return Redirect::to('home');
		}

		$stores = Store::with('roles')->order_by(Input::get('sort_by', 'stores.name'), Input::get('order', 'ASC'));
		if(Input::has('q'))
		{
			foreach(array('name', 'email') as $column)
			{
				$stores->or_where($column, '~*', Input::get('q'));
			}
		}
		$this->layout->content = View::make('admin.stores.index')
									 ->with('stores', $stores->paginate(10));
	}

	public function get_add()
	{
		if(Authority::cannot('create', 'Store'))
		{
			return Redirect::to('admin/stores/index');
		}

		$this->layout->content = View::make('admin.stores.add');
	}

	public function post_add()
	{
		$store = new Store;

		$errors = $store->validate_and_insert();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('admin/stores/add')
						 ->with('errors', $errors)
				   ->with_input('except', array('password'));
		}

		Notification::success('Successfully created store');

		return Redirect::to('admin/stores/index');
	}

	public function get_edit($id = 0)
	{
		$store = Store::find($id);

		if( ! $store OR $id == 0 OR Authority::cannot('update', 'Store', $store))
		{
			return Redirect::to('admin/stores/index');
		}

		$this->layout->content = View::make('admin.stores.edit')
									 ->with('store', $store);
	}

	public function put_edit($id = 0)
	{
		$store = Store::find($id);
		if( ! $store OR $id == 0)
		{
			return Redirect::to('admin/stores/index');
		}

		$errors = $store->validate_and_update();
		if(count($errors->all()) > 0)
		{
			return Redirect::to('admin/stores/edit')
						 ->with('errors', $errors)
				   ->with_input('except', array('password'));
		}

		Notification::success('Successfully updated store');

		return Redirect::to('admin/stores/index');
	}

	public function get_delete($id = 0)
	{
		$store = Store::find($id);

		if( ! $store OR $id == 0 OR Authority::cannot('delete', 'Store', $store))
		{
			return Redirect::to('admin/stores/index');
		}

		$this->layout->content = View::make('admin.stores.delete')
									 ->with('store', $store);
	}

	public function put_delete($id = 0)
	{
		$store = Store::find($id);
		if( ! $store OR $id == 0 OR Authority::cannot('delete', 'Store', $store))
		{
			return Redirect::to('admin/stores/index');
		}

		$store->delete();

		Notification::success('Successfully deleted store');

		return Redirect::to('admin/stores/index');
	}
}