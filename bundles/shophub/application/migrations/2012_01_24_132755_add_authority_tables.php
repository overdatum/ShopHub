<?php

use History\UUID;

class Shophub_Add_Authority_Tables {
	
	public function up()
	{
		Schema::create('accounts', function($table)
		{
			$table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'));
			$table->string('email')->unique();
			$table->string('password');
			$table->string('name');
			$table->uuid('language_uuid');
			$table->integer('version');
			$table->timestamp('created_at');
			$table->timestamp('updated_at');
		});

		Schema::create('roles', function($table)
		{
			$table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'));
			$table->string('name')->unique();
			$table->timestamps();
		});

		Schema::create('role_lang', function($table)
		{
			$table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'));
			$table->uuid('role_uuid');
			$table->uuid('language_uuid');
			$table->string('name');
			$table->string('description');
		});

		Schema::create('account_role', function($table)
		{
			$table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'));
			$table->uuid('account_uuid');
			$table->uuid('role_uuid');
			$table->timestamps();
		});

		Schema::create('languages', function($table)
		{
			$table->uuid('uuid')->default(DB::raw('uuid_generate_v4()'));
			$table->string('abbreviation');
			$table->string('name');
		});

		Schema::create('events', function($table)
		{
			$table->increments('id');
			$table->string('uuid')->index('event_uuid_index');
			$table->string('identifier');
			$table->integer('version');
			$table->timestamp('executed_at');
			$table->text('event');
		});

		$role = new Role(array(
			'name' => 'admin'
		));
		$admin_role_uuid = $role->save();

		$role = new Role(array(
			'name' => 'moderator'
		));
		$moderator_role_uuid = $role->save();
		
		$dutch_language_uuid = UUID::generate();
		$english_language_uuid = UUID::generate();
		DB::table('languages')->insert(array(
			array(
				'uuid' => $dutch_language_uuid,
				'abbreviation' => 'dut',
				'name' => 'Nederlands'
			),
			array(
				'uuid' => $english_language_uuid,
				'abbreviation' => 'eng',
				'name' => 'English'
			)
		));

		DB::table('role_lang')->insert(array(
			array(
				'uuid' => UUID::generate(),
				'role_uuid' => $admin_role_uuid,
				'language_uuid' => $dutch_language_uuid,
				'name' => 'Admin',
				'description' => 'De administrator'
			),
			array(
				'uuid' => UUID::generate(),
				'role_uuid' => $admin_role_uuid,
				'language_uuid' => $english_language_uuid,
				'name' => 'Admin',
				'description' => 'The administrator'
			),
			array(
				'uuid' => UUID::generate(),
				'role_uuid' => $moderator_role_uuid,
				'language_uuid' => $dutch_language_uuid,
				'name' => 'Moderator',
				'description' => 'De moderator'
			),
			array(
				'uuid' => UUID::generate(),
				'role_uuid' => $moderator_role_uuid,
				'language_uuid' => $english_language_uuid,
				'name' => 'Moderator',
				'description' => 'The moderator'
			)
		));

		Account::$accessible[] = 'password';
		$account = new Account(array(
			'email' => 'admin@admin.com',
			'password' => '$2a$08$P/FbYAoXjLhz2hKcoE75L.TIPEU9dKpcyJOz5w82XrD8i1lXz3UUi',
			'name' => 'Admin',
			'language_uuid' => '9322499a-0892-4d9e-ac47-833d9c94ef90',
			'created_at' => DB::raw('NOW()'),
			'updated_at' => DB::raw('NOW()'),
			'version' => 1
		));
		$account->roles()->sync(array($admin_role_uuid, $moderator_role_uuid));
		$account_uuid = $account->save();
	}

	public function down()
	{
		Schema::drop('accounts');
		Schema::drop('role_lang');
		Schema::drop('roles');
		Schema::drop('account_role');
		Schema::drop('languages');
		Schema::drop('events');
	}

}