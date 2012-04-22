<?php namespace Shophub;

use Exception;

use Laravel\Config;
use Laravel\Routing\Route;
use Laravel\Response;

class API {

	public static function call($method, $arguments, $input = array(), $segments = array())
	{
		$url = 'http://local.shophub.io/api/' . implode('/', $arguments) . (count($segments) > 0 ? '?' . http_build_query($segments) : '');

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
		);

		$data = json_encode($input);

		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_USERPWD, "loginname:passwort");
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if(strtoupper($method) != 'GET')
		{
			if(strtoupper($method) != 'DELETE')
			{
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}

			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		elseif(count($segments) > 0)
		{

		}

		$response = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		return json_decode($response);
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

	public static function delete($arguments, $input = array())
	{
		return static::call('DELETE', $arguments, $input);
	}

}