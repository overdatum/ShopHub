<?php namespace History\Eloquent;

use DateTime;
use Exception;
use Laravel\Database\Eloquent\Relationships\Has_Many_And_Belongs_To as Eloquent_Has_Many_And_Belongs_To;

class Has_Many_And_Belongs_To extends Eloquent_Has_Many_And_Belongs_To {

	protected $with = array('uuid', 'created_at', 'updated_at');

	public function sync($ids, $ignore = null)
	{
		if( ! is_array($ids)) return true;

		$key = array_search($ignore, $ids);
		if($key !== false) unset($ids[$key]);

		$current = $this->pivot()->lists($this->other_key());
		
		$attach_ids = $detach_ids = array();
		
		foreach ($ids as $id)
		{
			if ( ! in_array($id, $current))
			{
				$attach_ids[] = $id;
			}
		}

		foreach ($current as $id)
		{
			if( ! in_array($id, $ids))
			{
				$detach_ids[] = $id;
			}	
		}

		if(count($attach_ids) > 0)
		{
			$method = "attach_to_".strtolower(get_class($this->base))."_event";
			if( ! method_exists($this->model, $method)) throw new Exception("Method [" . $method . "] not found on the model");

			$event = $this->model->$method();
			$event->uuid = $this->base->uuid;
			$event->created_at = new DateTime;
			$event->updated_at = $event->created_at;
			$event->{strtolower(get_class($this->model)) . '_uuids'} = $attach_ids;

			$this->base->events[] = $event;
		}

		if (count($detach_ids) > 0)
		{
			$method = "detach_from_".strtolower(get_class($this->base))."_event";
			if( ! method_exists($this->model, $method)) throw new Exception("Method [" . $method . "] not found on the model");
			
			$event = $this->model->$method();
			$event->uuid = $this->base->uuid;
			$event->{strtolower(get_class($this->model)) . '_uuids'} = $detach_ids;

			$this->base->events[] = $event;
		}

		return true;
	}

}