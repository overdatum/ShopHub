<?php
class Admin_Dashboard_Controller extends Controller {
    
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
			'title' => 'Admin | Dashboard'
		);

		$this->layout = View::make('layouts.default')->with('header_data', $header_data)->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_index()
	{
		$this->layout->content = View::make('admin.dashboard')->with('account', $account);
	}

}