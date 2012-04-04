<?php namespace EventSourcing;

use Laravel\Database\Eloquent\Model as Eloquent;

class Model extends Eloquent {

	public static $key = 'id';

	public function save()
	{
		if ( ! $this->dirty()) return true;

		if (static::$timestamps)
		{
			$this->timestamp();
		}

		if ($this->exists)
		{
			$this->event = $this->update_event();
			foreach($this->get_dirty() as $key => $value)
			{
				if($key == 'event') continue;

				$this->event->$key = $value;
			}
		}
		else
		{
			$this->event = $this->create_event();
			
			foreach($this->attributes as $key => $value)
			{
				if($key == 'event') continue;
				
				$this->event->$key = $value;
			}
		}

		$this->original = $this->attributes;

		return true;
	}

	public function delete()
	{
		$this->event = $this->delete_event();
		$this->event->uuid = $this->get_key();
		
		return true;
	}

	public function sync()
	{
		var_dump($this->attributes); die;
		return true;
	}

}