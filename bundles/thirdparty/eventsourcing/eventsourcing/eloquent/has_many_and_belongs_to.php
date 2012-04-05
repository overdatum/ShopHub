<?php namespace EventSourcing\Eloquent;

use Laravel\Database\Eloquent\Relationships\Has_Many_And_Belongs_To as Eloquent_Has_Many_And_Belongs_To;

class Has_Many_And_Belongs_To extends Eloquent_Has_Many_And_Belongs_To {
	
	/**
	 * Insert a new record into the joining table of the association.
	 *
	 * @param  int    $id
	 * @param  array  $joining
	 * @return bool
	 */
	public function attach($id, $attributes = array())
	{
		$event = $this->model->{"attach_to_".strtolower(get_class($this->base))."_event"}();
		$event->uuids = $id;
		$this->model->events[] = $event;

		return true;
	}

	/**
	 * Detach a record from the joining table of the association.
	 *
	 * @param  int   $ids
	 * @return bool
	 */
	public function detach($ids)
	{
		if ( ! is_array($ids)) $ids = array($ids);

		$event = $this->model->{"detach_from_".strtolower(get_class($this->base))."_event"}();
		$event->uuids = $ids;
		$this->model->events[] = $event;

		return true;
	}

	public function sync($ids)
	{
		$current = $this->pivot()->lists($this->other_key());
		
		$attach_ids = $detach_ids = array();
		
		// First we need to attach any of the associated models that are not currently
		// in the joining table. We'll spin through the given IDs, checking to see
		// if they exist in the array of current ones, and if not we insert.
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

		echo '<b>Current</b><br>';
		var_dump($current);

		echo '<b>New</b><br>';
		var_dump($ids);

		echo '<b>Attach</b><br>';
		var_dump($attach_ids);

		echo '<b>Detach</b><br>';
		var_dump($detach_ids);
		
		die;
		// Next we will take the difference of the current and given IDs and detach
		// all of the entities that exists in the current array but are not in
		// the array of IDs given to the method, finishing the sync.
		$detach_ids = array_diff($current, $ids);

		if (count($detach) > 0)
		{
			$this->detach($detach_ids);
		}
	}

}