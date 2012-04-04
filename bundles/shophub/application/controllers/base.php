<?php

class Shophub_Base_Controller extends Controller {

	public $restful = true;

	public $layout = true;

	public function layout()
	{
		$this->layout = View::make('shophub::layouts.default')->with('header_data', array(
			'title' => $this->meta_title
		));

		return $this->layout;
	}

}