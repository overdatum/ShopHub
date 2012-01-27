<?php
class Frontend_Home_Controller extends Controller {

	public $restful = true;
	public $layout = true;

	public function layout()
	{
		$extra = Auth::check() ? Config::get('menus.logged_in.frontend') : Config::get('menus.logged_out.frontend');

		$menu_data = array(
			'menu' => array_merge(Config::get('menus.frontend'), $extra)
		);

		$header_data = array(
			'title' => 'Home'
		);

		$this->layout = View::make('layouts.default')->with('header_data', $header_data)->with('menu_data', $menu_data);

		return $this->layout;
	}

	public function get_index()
	{
		$this->layout->content = View::make('frontend.home.index');
	}

}