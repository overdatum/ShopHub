<?php namespace Shophub;

use Exception;

use Laravel\Config;
use Laravel\Routing\Route;
use Laravel\Response;

class API {

	public static function call($method, $arguments, $input = array(), $segments = array())
	{
		$url = 'http://local.shophub.io/api/' . implode('/', $arguments) . (count($segments) > 0 ? '?' . http_build_query($segments) : '');

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
		
		if($method !== 'GET')
		{
 			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		$response = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		switch ($code) {
			case 401:
				throw new Exception("401 Unauthorized");	
			break;

			case 404:
				throw new Exception("404 Not Found: We couldn't find the resource you're looking for. Please check the documentation and try again");
			break;

			case 500:
				throw new Exception("500 Internal Server Error: Sorry, we've run into a problem. Please try again or contact support");
			break;
			
			case 400:
				return (object) array(
					'code' => 400,
					'errors' => json_decode($response)
				);
			break;

			default:
				return json_decode($response);
			break;
		}
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