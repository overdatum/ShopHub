<?php //namespace Laravel;

class Notification {

	public static $notifications = array();

	public static function success($message, $time = 5000, $close = false)
	{
		static::add('success', $message, $time);
	}

	public static function error($message, $time = 5000, $close = false)
	{
		static::add('error', $message, $time);
	}

	public static function warning($message, $time = 5000, $close = false)
	{
		static::add('warning', $message, $time);
	}

	public static function info($message, $time = 5000, $close = false)
	{
		static::add('info', $message, $time);
	}

	public static function show()
	{
		$notifications = Session::get('notifications');
		if(count($notifications) > 0)
		{
			echo '<div class="alert-messages">';
			foreach($notifications as $notification) {
				echo '<div class="alert alert-'.$notification['type'].'">';
					if($notification['close']) echo '<a class="close">×</a>';
					echo $notification['message'];
				echo '</div>';
			}
			echo '</div>';
		}
	}

	protected static function add($type, $message, $time)
	{
		static::$notifications[] = array(
			'type' => $type,
			'message' => $message,
			'time' => $time
		);
		Session::flash('notifications', static::$notifications);
	}

}