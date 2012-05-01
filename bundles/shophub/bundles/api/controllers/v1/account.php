<?php

class ShopHub_Api_V1_Account_Controller extends Controller {

	public $restful = true;

	public function get_test($uuid)
	{
		$data = array(
			'Hello' => 'World!'
		);
		
		return $this->service->response(200, $data);
	}

}