<?php

use EventSourcing\Model;

class Role extends Model {

	public $includes;

	public static $table = 'roles';

	public function __construct()
	{
		$this->includes = array('lang' => function($query)
		{
			$query->where_language_id(1);
		});
	}

	public function accounts()
	{
		return $this->has_many_and_belongs_to('Account');
	}

	public function lang()
	{
		return $this->has_one('RoleLang');
	}

	public function sync_event($assign_uuids, $unassign_uuids)
	{
		return array(
			new RolesAssignedToAccount($this->id, $assign_uuids),
			new RolesUnassignedFromAccount($this->id, $unassign_uuids)
		);
	}

}