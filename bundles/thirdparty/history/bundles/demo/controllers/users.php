<?php

use History\Bus;
use History\EventStore;
use History\Libraries\UUID;
use History\EventValidator;

use Demo\Events\V2\UserRegistered;
use Demo\Events\V2\UserUpdated;
use Demo\Events\V1\UserDeleted;

use Demo\Repositories\UserRepository;

class Demo_Users_Controller extends Controller {
	
	public $restful = true;

	public function __construct()
	{
		Asset::container('header')->bundle('es')->add('style', 'css/style.css');
	}

	public function get_index()
	{
		$users = DB::table('users')->get();
		return View::make('demo::users.index')->with('users', $users);
	}

	public function get_add()
	{
		return View::make('demo::users.add');
	}

	public function post_add()
	{
		$event = new UserRegistered(UUID::generate(), Input::get('first_name'), Input::get('last_name'));
		EventStore::save_event($event);
		Bus::publish($event);

		return Redirect::to('demo/users/index')->with('message', 'Successfully added user');
	}

	public function get_edit($uuid)
	{
		$user = UserRepository::find($uuid);
		
		Session::flash('version', $user->version);

		return View::make('demo::users.edit')->with('user', $user);
	}

	public function put_edit()
	{
		$event = new UserUpdated(Input::get('uuid'), Input::get('first_name'), Input::get('last_name'));

		$validator = new EventValidator($event, Session::get('version'));
		if($validator->fails())
		{
			var_dump($validator->conflicts); die;
			return Redirect::to('demo/users/edit')->with('message', 'Somebody else changed the same user and it resulted in a conflict, please merge...')->with_input()->with('conflicts', $validator->conflicts);
		}
		
		EventStore::save_event($event);
		Bus::publish($event);

		return Redirect::to('demo/users/index')->with('message', 'Successfully edited user');
	}

	public function get_delete($uuid)
	{
		$user = DB::table('users')->where_uuid($uuid)->first();

		return View::make('demo::users.delete')->with('user', $user);
	}

	public function put_delete()
	{
		$data = array(
			'uuid' => Input::get('uuid')
		);
		Bus::publish(new UserDeleted($data));

		return Redirect::to('demo/users/index')->with('message', 'Successfully deleted user');
	}

}