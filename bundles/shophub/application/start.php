<?php

// Bootstrapping ShopHub

Bundle::register('menu', array(
	'auto' => true,
	'location' => 'thirdparty/menu'
));
Bundle::start('menu');

Bundle::register('history', array(
	'auto' => true,
	'location' => 'thirdparty/history/application'
));
Bundle::start('history');

Bundle::register('authority', array(
	'auto' => true,
	'location' => 'thirdparty/authority'
));
Bundle::start('authority');

Bundle::register('service', array(
	'auto' => true,
	'location' => 'thirdparty/service'
));
Bundle::start('service');

Autoloader::map(array(
	'Shophub_Base_Controller' => __DIR__ . DS . 'controllers/base.php',
));

Autoloader::directories(array(
	__DIR__ . DS . 'models'
));

Autoloader::namespaces(array(
	'ShopHub\\Domain' => __DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain',
	'API' => __DIR__ . DS . '..' . DS . 'bundles' . DS . 'api',
	'Application' => __DIR__,
	'ShopHub' => __DIR__ . DS . '..' . DS . 'shophub',
));

Autoloader::alias('ShopHub\\Profiling\\Profiler', 'Profiler');

require_once __DIR__ . DS . '..' . DS . 'shophub' . DS . 'helpers' . EXT;

$bundles = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain', FilesystemIterator::SKIP_DOTS);
foreach ($bundles as $bundle)
{
	if ($bundle->isDir() && file_exists(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain' . DS . $bundle->getFilename() . DS . 'start.php'))
	{
		Bundle::register($bundle->getFilename(), array(
			'auto' => true,
			'location' => 'shophub' . DS . 'bundles' . DS . 'domain' . DS . $bundle->getFilename()
		));

		Bundle::start($bundle->getFilename());
	}
}

$services = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'api', FilesystemIterator::SKIP_DOTS);
foreach ($services as $service)
{
	require __DIR__ . DS . '..' . DS . 'bundles' . DS . 'api' . DS . $service->getFilename();
}

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('auth/login');
});

Filter::register('can', function($action, $resource)
{
	if ( ! Authority::can($action, $resource)) return Redirect::to('auth/login');
});

View::composer('shophub::layouts.default', function($view)
{
	Asset::container('header')->add('jquery', 'js/jquery.min.js');
	Asset::container('header')->add('bootstrap', 'bootstrap/bootstrap.css');
	Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-buttons.js', 'jquery');
	Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-dropdown.js', 'jquery');
	Asset::container('header')->add('main', 'css/main.css');

	$view->footer = View::make('shophub::partials.footer');
});