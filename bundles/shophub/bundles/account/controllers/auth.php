<?php

class Account_Auth_Controller extends Shophub_Base_Controller {
	
	public $meta_title = 'Authentication';

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
				return Redirect::to('backend/accounts');
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