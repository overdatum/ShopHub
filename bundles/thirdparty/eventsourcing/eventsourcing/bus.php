<?php namespace EventSourcing;

class Bus {
	
	/**
	 * Listen for an event
	 * @param  string   $channel  The event you want to listen for (prefixed with "es: ")
	 * @param  Closure $callback The function that has to be called when the event arrives
	 * @return Void
	 */
	public static function listen($channel, $callback)
	{
		Message::subscribe($channel, $callback);
	}

	/**
	 * Publish an event to the listeners
	 * @param  Event $event
	 * @return Void
	 */
	public static function publish($event)
	{
		$identifier = get_class($event);
		Message::publish("es: {$identifier}", array($event));
	}

}