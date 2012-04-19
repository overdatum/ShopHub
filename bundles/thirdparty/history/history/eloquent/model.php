<?php namespace History\Eloquent;

use Laravel\Database\Eloquent\Model as Eloquent_Model;
use Laravel\Validator;

use History\UUID;
use History\EventStore;
use History\Bus;

class Model extends Eloquent_Model {

	public static $key = 'uuid';

	public $events = array();

	public static $rules = array();

	public static $versioned = false;

	public function __construct($attributes = array(), $exists = false)
	{
		$this->exists = $exists;

		if( ! $exists)
		{
			$attributes['uuid'] = UUID::generate();
		}

		$this->fill($attributes);
	}

	public static function from_session($session_key)
	{
		return unserialize(Session::get($session_key));
	}

	public function save()
	{
		if( ! $this->dirty() && count($this->events) == 0) return true;

		if( ! $this->dirty())
		{
			$this->publish();
			return true;
		}

		if (static::$timestamps)
		{
			$this->timestamp();
		}

		if(static::$rules)
		{
			$validator = Validator::make($this->attributes, static::$rules);
			if( ! $validator->valid())
			{
				$this->errors = $validator->errors;

				return false;
			}
		}

		if ($this->exists)
		{
			$event = $this->update_event();
			$event->uuid = $this->get_key();
			foreach($this->get_dirty() as $key => $value)
			{
				if($key == 'event') continue;
				$event->$key = $value;
			}

			array_unshift($this->events, $event);
		}
		else
		{
			$event = $this->create_event();
			foreach($this->attributes as $key => $value)
			{
				if($key == 'event') continue;
				$event->$key = $value;
			}

			array_unshift($this->events, $event);
		}

		$this->original = $this->attributes;

		$this->publish();

		return isset($this->uuid) ? $this->uuid : true;
	}

	public function delete()
	{
		$event = $this->delete_event();
		$event->uuid = $this->get_key();

		array_unshift($this->events, $event);
		
		$this->publish();

		return true;
	}

	public function dirty()
	{
		if( ! $this->exists) return true;

		foreach ($this->get_dirty() as $key => $value)
		{
			if($key != 'updated_at') return true;
		}
		
		return false;
	}

	public function publish()
	{
		EventStore::save_events($this->events, static::$versioned);
		Bus::publish($this->events);
	}

	public function has_many_and_belongs_to($model, $table = null, $foreign = null, $other = null)
	{
		return new Has_Many_And_Belongs_To($this, $model, $table, $foreign, $other);
	}
}