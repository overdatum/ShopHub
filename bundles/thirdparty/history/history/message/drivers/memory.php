<?php namespace History\Message\Drivers;

use Laravel\Event;
use Closure;

class Memory {

	public function listeners()
	{
		$listeners = array();
		foreach (Event::$events as $listener => $handlers) {
			if(starts_with($listener, 'es'))
				$listeners[] = str_replace('es: ', '', $listener);
		}

		return $listeners;
	}

	/**
	 * Publish a message to a channel
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function publish($channel, $arguments)
	{
		return Event::fire($channel, $arguments);
	}

	/**
	 * Add subsciption for channel
	 *
	 * @param  string   $channel
	 * @param  closure  $callback
	 * @return void
	 */
	public function subscribe($channel, Closure $callback)
	{
		Event::listen($channel, $callback);
	}

}