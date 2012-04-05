<?php namespace EventSourcing\Eloquent;

use Laravel\Database\Eloquent\Model as Eloquent_Model;

class Model extends Eloquent_Model {

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
			$event = $this->update_event();
			foreach($this->get_dirty() as $key => $value)
			{
				if($key == 'event') continue;

				$event->$key = $value;
			}

			$this->events[] = $event;
		}
		else
		{
			$event = $this->create_event();
			foreach($this->attributes as $key => $value)
			{
				if($key == 'event') continue;
				
				$event->$key = $value;
			}

			$this->events[] = $event;
		}

		$this->original = $this->attributes;

		return true;
	}

	public function delete()
	{
		$event = $this->delete_event();
		$event->uuid = $this->get_key();

		$this->events[] = $event;
		
		return true;
	}

	public function has_many_and_belongs_to($model, $table = null, $foreign = null, $other = null)
	{
		return new Has_Many_And_Belongs_To($this, $model, $table, $foreign, $other);
	}
}