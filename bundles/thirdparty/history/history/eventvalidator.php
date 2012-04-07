<?php namespace History;

class EventValidator {
	
	public $event;
	public $version;
	public $conflicts;

	public function __construct($event, $version)
	{
		$this->event = $event;
		$this->version = $version;
	}

	public function fails()
	{
		$last_version = EventStore::get_last_version($this->event->uuid);
		if($this->version != $last_version)
		{
			// Get new events that happened meanwhile
			$new_events = EventStore::get_events_from_version($this->event->uuid, $this->version + 1);
			
			// Does the event conflict?
			if($this->conflicts($new_events))
			{
				return true;
			}
			else {
				$event->version = $last_version;
			}
		}
		
		return false;
	}

	public function conflicts($new_events)
	{
		$conflicts = array();
		foreach ($new_events as $new_event)
		{
			foreach($new_event as $key => $value)
			{
				if($key != 'version' && array_key_exists($key, $this->event) && $value != $this->event->$key)
				{
					$conflicts[] = $key;
				}	
			}
		}

		$this->conflicts = $conflicts;

		return count($conflicts) > 0 ? true : false;
	}

}