<?php

use History\Eloquent\Model;

use Application\Events\V1\LanguageRegistered;
use Application\Events\V1\LanguageUpdated;
use Application\Events\V1\LanguageDeleted;

class Language extends Model {

	public static $table = 'languages';

	public static $key = 'uuid';

	public function create_event()
	{
		return new LanguageRegistered;
	}

	public function update_event()
	{
		return new LanguageUpdated;
	}

	public function delete_event()
	{
		return new LanguageDeleted;
	}

}