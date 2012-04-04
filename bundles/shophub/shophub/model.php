<?php namespace ShopHub;

class Model {
	
	public static $accessible;

	public $attributes;

	public $errors;

	public function __construct($attributes = array())
	{
		$this->fill($attributes);
	}

	public function fill($attributes)
	{
		$attributes = (array) $attributes;

		foreach ($attributes as $key => $value)
		{
			// If the "accessible" property is an array, the developer is limiting the
			// attributes that may be mass assigned, and we need to verify that the
			// current attribute is included in that list of allowed attributes.
			if (is_array(static::$accessible))
			{
				if( ! array_key_exists($key, static::$accessible)) continue;
				
				$this->attributes[$key] = $value;
			}

			// If the "accessible" property is not an array, no attributes have been
			// white-listed and we are free to set the value of the attribute to
			// the value that has been passed into the method without a check.
			else
			{
				$this->attributes[$key] = $value;
			}
		}
	}

	public function validate()
	{
		return true;
	}

	public function save()
	{
		if( ! $valid = $this->validate()) return false;
		
		EventStore::save_event($event);
	}

	public function get_event()
	{
		
	}

}