<?php

class History_Add_Events_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		/*Schema::create('events', function($table)
		{
			$table->increments('id');
			$table->string('uuid')->index('event_uuid_index');
			$table->string('identifier');
			$table->integer('version');
			$table->timestamp('executed_at');
			$table->text('event');
		});*/
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		/*
		Schema::drop('events');
		*/
	}

}