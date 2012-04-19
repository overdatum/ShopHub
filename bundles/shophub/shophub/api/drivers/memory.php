<?php namespace Api\Drivers;

class Memory {

	public function call($arguments, $input = array())
	{
		$class = array_shift($arguments);
		$method = array_shift($arguments);
		$old_input = Input::$input;
		Input::$input = $input;
		$result = call_user_func_array(array($class, $method), $arguments);
		Input::$input = $old_input;
		return $result;
	}

}