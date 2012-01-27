<?php

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - 

 Title : Sample Landing page for PHP Quick Profiler Class
 Author : Created by Ryan Campbell
 URL : http://particletree.com/features/php-quick-profiler/

 Last Updated : April 22, 2009

 Description : This file contains the basic class shell needed
 to use PQP. In addition, the init() function calls for example
 usages of how PQP can aid debugging. See README file for help
 setting this example up.

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

require __DIR__.'/src/classes/PhpQuickProfiler.php';
//require_once('classes/MySqlDatabase.php');

class PQP {
	
	private $profiler;
	private $db = '';
	
	public function __construct()
	{
		$this->profiler = new PQP\PhpQuickProfiler(PQP\PhpQuickProfiler::getMicroTime());
	}

	public static function init() {
		self::sampleConsoleData();
		self::sampleDatabaseData();
		self::sampleMemoryLeak();
		self::sampleSpeedComparison();
	}
	
	public static function sampleConsoleData() {
		try {
			PQP\Console::log('Begin logging data');
			PQP\Console::logMemory($this, 'PQP Example Class : Line '.__LINE__);
			PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
			PQP\Console::log(array('Name' => 'Ryan', 'Last' => 'Campbell'));
			PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
			PQP\Console::logMemory($this, 'PQP Example Class : Line '.__LINE__);
			PQP\Console::log('Ending log below with a sample error.');
			throw new Exception('Unable to write to log!');
		}
		catch(Exception $e) {
			PQP\Console::logError($e, 'Sample error logging.');
		}
	}
	
	public static function sampleDatabaseData() {
		DB::table('accounts')->find(1);
		/*$this->db = new MySqlDatabase(
			'your DB host', 
			'your DB user',
			'your DB password');
		$this->db->connect(true);
		$this->db->changeDatabase('your db name');
		
		$sql = 'SELECT PostId FROM Posts WHERE PostId > 2';
		$rs = $this->db->query($sql);
		
		$sql = 'SELECT COUNT(PostId) FROM Posts';
		$rs = $this->db->query($sql);
		
		$sql = 'SELECT COUNT(PostId) FROM Posts WHERE PostId != 1';
		$rs = $this->db->query($sql);*/
	}
		
	public static function sampleMemoryLeak() {
		$ret = '';
		$longString = 'This is a really long string that when appended with the . symbol 
					  will cause memory to be duplicated in order to create the new string.';
		for($i = 0; $i < 10; $i++) {
			$ret = $ret . $longString;
			PQP\Console::logMemory($ret, 'Watch memory leak -- iteration '.$i);
		}
	}
		
	public static function sampleSpeedComparison() {
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
		PQP\Console::logSpeed('Time taken to get to line '.__LINE__);
	}
	
	public function __destruct() {
		$pqp = $this->profiler->display($this->db);

		$config = Config::get('pqp::pqp');
		foreach($config['layouts'] as $layout)
		{
			Asset::container('header')->add('pqp', 'bundles/pqp/css/pQp.css');
			View::composer($layout, function($view) use ($pqp)
			{
				Asset::container('header')->add('jquery', 'js/jquery.min.js');
				Asset::container('header')->add('main', 'css/main.css');
				Asset::container('header')->add('bootstrap', 'bootstrap/bootstrap.min.css');
				Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-modal.js', 'jquery');
				Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-dropdown.js', 'jquery');
				$view->footer = $pqp.View::make('partials.footer');
			});
		}

	}
	
}