<?php namespace ShopHub;

use Exception;

use ShopHub\Profiling\Profiler;
use Laravel\Config;
use Laravel\Routing\Route;
use Laravel\Response;

class API {

	public static function call($method, $arguments, $input = array(), $segments = array())
	{
		if(Config::get('application.profiler'))
		{
			$segments = $segments + array('pretty' => true);
		}

		$url = 'http://local.shophub.io/api/v1/' . implode('/', $arguments) . (count($segments) > 0 ? '?' . http_build_query($segments) : '');

		$method = strtoupper($method);

		$headers = array(
			'accept: application/json',
			'content-type: application/json',
		);

		$data = json_encode($input);

		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_USERPWD, "loginname:passwort");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		
		if($method !== 'GET' && $method !== 'DELETE')
		{
 			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		$body = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		Profiler::api($code, $method, $url, $body, $input);

		return new APIResponse($code, json_decode($body));
	}

	public static function get($arguments, $segments = array())
	{
		return static::call('GET', $arguments, array(), $segments);
	}

	public static function post($arguments, $input = array())
	{
		return static::call('POST', $arguments, $input);
	}

	public static function put($arguments, $input = array())
	{
		return static::call('PUT', $arguments, $input);
	}

	public static function delete($arguments)
	{
		return static::call('DELETE', $arguments, array());
	}

}