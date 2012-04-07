<?php

use History\Setting;
use History\EventStore;

class History_Events_Controller extends Controller {
	
	public $restful = true;

	public function get_index()
	{
		Asset::container('header')->bundle('es')->add('style', 'css/style.css')->add('main', 'js/main.js');

		$events = EventStore::all(Input::get('start', 0), 10);

		foreach(Setting::get('eventhandlers') as $file => $enabled)
		{
			$eventhandler = require $file;
			$eventhandlers[$file] = array_merge($eventhandler, array('enabled' => $enabled));
		}

		return View::make('history::events.index')->with('events', $events)->with('eventhandlers', $eventhandlers);
	}

	public function post_replay()
	{

		/**
		 * Scheduler
		 * 
		 * The scheduler checks if any systemevent has to be run
		 */
		

		/**
		 * Replayer
		 * 
		 * The replayer allows you to run commands on a filtered set of events.
		 */

		/**
		 * All eventhandlers that you add are inactive by default. You can activate an eventhandler on a given moment, for a period of time or for a single replay.
		 */
		Bus::publish(new SystemEvent_ActivateEventHandler('eventhandlers/V2/user.php'));

		/**
		 * Ofcourse, you can also deactive an eventhandler, again, you can do this on a given moment, for a period of time.
		 */
		Bus::publish(new SystemEvent_DeactivateEventHandler('eventhandlers/V1/user.php'));

		/**
		 * If you want to replace a commandhandler you can do so
		 */
		Bus::publish(new SystemEvent_ReplaceEventHandler('eventhandlers/V1/user.php', 'eventhandlers/V2/user.php'));

		/**
		 * What if, instead of having "first_name" and a "last_name" fields we want to have a "full_name" field only.
		 * We replace the eventhandler with a new one that stores / updates the "full_name" into / for a column called "full_name".
		 * We also add an EventMigrator that will take any V1 commands and migrate them to a V2 command.
		 * This way, we can replay the log, change V1 events into V2 events and *only* execute the new eventhandler (V2).
		 */
		Bus::publish(new SystemEvent_ReplaceEventHandlerAndActivateEventMigrator('eventhandlers/V1/user.php', 'eventhandlers/V2/user.php', 'eventmigrators/V1V2/user.php'));
	}

}