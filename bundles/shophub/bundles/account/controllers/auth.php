<?php
class Account_Auth_Controller extends Controller {
	
	public $restful = true;
	
	public $layout = true;

	public function layout()
	{
		$extra = Auth::check() && URI::segment(2) != 'logout' ? Config::get('menus.logged_in.frontend') : Config::get('menus.logged_out.frontend');

		$menu_data = array(
			'menu' => array_merge(Config::get('menus.frontend'), $extra)
		);

		$header_data = array(
			'title' => 'Account'
		);
		
		$this->layout = View::make('shophub::layouts.default')->with('header_data', $header_data)->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_login()
	{
		$this->layout->content = View::make('account::auth.login');
	}

	public function put_login()
	{
		$rules = array(
			'email' => 'required|email',
			'password' => 'required',
		);

		$validator = new Validator(Input::all(), $rules);
		if ($validator->valid())
		{
			if (Auth::attempt(Input::get('email'), Input::get('password')))
			{
				return Redirect::to('profile');
			}
		}

		return Redirect::to('auth/login')->with('errors', $validator->errors);
	}

	public function get_logout()
	{
		Auth::logout();

		$this->layout->content = View::make('account::auth.logout');
	}
}