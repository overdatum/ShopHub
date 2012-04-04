<?php namespace EventSourcing\EventStore\Drivers;

use Laravel\Database as DB;

class PDO {

	/**
	 * Get all events for an Entity by it's UUID
	 *
	 * @param $uuid An Entity's UUID
	 *
	 * @return array
	 */
	public function get_all_events($uuid)
	{
		return $this->get_events_from_version($uuid, 0);
	}

	/**
	 * Get all events for an Entity by it's UUID from a given version
	 *
	 * @param $uuid An Entity's UUID
	 * @param $version The first event version
	 *
	 * @return array
	 */
	public function get_events_from_version($uuid, $from_version, $to_version = null)
	{
		$events = array();

		$query = DB::table('events')->where_uuid($uuid)->where('version', '>=', $from_version)->order_by('id', 'ASC');
		if($to_version)
		{
			$query->where('version', '<=', $to_version);
		}
		$rows = $query->get(array('event', 'version'));

		foreach($rows as $row)
		{
			$event = unserialize($row->event);
			$event->version = $row->version;
			$events[] = $event;
		}

		return $events;
	}

	/**
	 * Get last version for an Entity by it's UUID
	 *
	 * @param $uuid An Entity's UUID
	 *
	 * @return array
	 */
	public function get_last_version($uuid)
	{
		$version = DB::table('events')->where_uuid($uuid)->max('version');
		return is_null($version) ? 0 : $version;
	}

	/**
	 * Add an Event to the EventStore for an Entity
	 *
	 * @param UUID $uuid An Entity's UUID
	 * @param Event $event An Event
	 *
	 * @return void
	 */
	public function save_event($event)
	{
		$version = $this->get_last_version($event->uuid) + 1;
		DB::table('events')->insert(
			array(
				'uuid' => $event->uuid,
				'identifier' => get_class($event),
				'event' => serialize($event),
				'version' => $version,
				'executed_at' => 'NOW()'
			)
		);

		$event->version = $version;
	}

	public function all($skip, $take)
	{
		return DB::table('events')->skip($skip)->take($take)->get();
	}

}
