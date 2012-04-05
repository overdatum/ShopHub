<?php namespace EventSourcing\Eloquent;

use Laravel\Database\Eloquent\Relationships\Has_Many_And_Belongs_To as Eloquent_Has_Many_And_Belongs_To;
use Exception;

class Has_Many_And_Belongs_To extends Eloquent_Has_Many_And_Belongs_To {

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
				$attach_ids[] = (int) $id;
			}
		}

		foreach ($current as $id)
		{
			if( ! in_array($id, $ids))
			{
				$detach_ids[] = (int) $id;
			}	
		}
		
		if(count($attach_ids) > 0)
		{
			$method = "attach_to_".strtolower(get_class($this->base))."_event";
			
			if( ! method_exists($this->model, $method)) throw new Exception("Method [" . $method . "] not found on the model");

			$event = $this->model->$method();
			$event->uuids = $attach_ids;
			$this->base->events[] = $event;

			return true;
		}

		if (count($detach_ids) > 0)
		{
			$method = "detach_from_".strtolower(get_class($this->base))."_event";

			if( ! method_exists($this->model, $method)) throw new Exception("Method [" . $method . "] not found on the model");

			$event = $this->model->$method();
			$event->uuids = $detach_ids;
			$this->base->events[] = $event;

			return true;
		}
	}

}