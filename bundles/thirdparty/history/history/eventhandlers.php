<?php namespace History;

use FilesystemIterator as fIterator;

class EventHandlers {

	public static $handlers = array();

	public static $allowed_events = array();

	public static function register($dirs)
	{
		if( ! is_array($dirs)) $dirs = array($dirs);

		foreach ($dirs as $dir)
		{
			foreach (static::files($dir, true) as $file)
			{
				/*if(count(static::$allowed_events) > 0 && ! in_array($file, static::$allowed_events)) continue;

				if(array_key_exists($file, $enabled_listeners))
				{
					if($enabled_listeners[$file] == true || (count(static::$allowed_events) > 0 && in_array($file, static::$allowed_events)))
					{*/
						static::$handlers[$file] = require $file;
					/*}
				}
				// This shoud not be here...
				else
				{
					Setting::set('eventhandlers', array_merge($enabled_listeners, array($file => false)));
				}*/
			}
		}
	}

	public static function files($directory, $recursive = false, $options = fIterator::SKIP_DOTS)
	{
		$files = array();
		$items = new fIterator($directory, $options);
		foreach ($items as $item)
		{
			$full_path = $item->getPath().DS.$item->getFilename();
			if( ! $item->isDir())
			{
				if(ends_with($full_path, '.php'))
				{
					$files[] = $full_path;
				}
			}
			elseif($recursive) {
				$files = array_merge($files, static::files($full_path, true, $options));
			}
		}

		return $files;
	}

}