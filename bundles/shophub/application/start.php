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

define('SH_CORE', __DIR__ . DS . '..' . DS . 'shophub');

Autoloader::map(array(
	'ShopHub\\Profiling\\Profiler' => SH_CORE . DS . 'profiling' . DS . 'profiler.php',
	'ShopHub\\API' => SH_CORE . DS . 'api.php',
	'ShopHub\\APIResponse' => SH_CORE . DS . 'apiresponse.php',
	'ShopHub\\Service' => SH_CORE . DS . 'service.php',
));

Autoloader::namespaces(array(
	'ShopHub' => __DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain',
));

require_once __DIR__ . DS . '..' . DS . 'shophub' . DS . 'helpers' . EXT;

$shophub_domains = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain', FilesystemIterator::SKIP_DOTS);
foreach ($shophub_domains as $shophub_domain)
{
	if ($shophub_domain->isDir() && file_exists(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'domain' . DS . $shophub_domain->getFilename() . DS . 'start.php'))
	{
		Bundle::register('shophub_domain_' . $shophub_domain->getFilename(), array(
			'auto' => true,
			'location' => 'shophub' . DS . 'bundles' . DS . 'domain' . DS . $shophub_domain->getFilename()
		));

		Bundle::start('shophub_domain_' . $shophub_domain->getFilename());
	}
}

$client_domains = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'client', FilesystemIterator::SKIP_DOTS);
foreach ($client_domains as $client_domain)
{
	if ($client_domain->isDir() && file_exists(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'client' . DS . $client_domain->getFilename() . DS . 'start.php'))
	{
		Bundle::register('shophub_client_' . $client_domain->getFilename(), array(
			'auto' => true,
			'location' => 'shophub' . DS . 'bundles' . DS . 'client' . DS . $client_domain->getFilename()
		));

		Bundle::start('shophub_client_' . $client_domain->getFilename());
	}
}

$service_versions = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'api', FilesystemIterator::SKIP_DOTS);
foreach ($service_versions as $service_version)
{
	$services = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles' . DS . 'api' . DS . $service_version->getFilename(), FilesystemIterator::SKIP_DOTS);
	foreach ($services as $service)
	{	
		require __DIR__ . DS . '..' . DS . 'bundles' . DS . 'api' . DS . $service_version->getFilename() . DS . $service->getFilename();
	}
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
	Asset::container('header')->add('jquery', 'js/jquery.min.js')
		->add('bootstrap', 'bootstrap/bootstrap.css')
		->add('main', 'css/main.css');

	Asset::container('footer')->add('bootstrap', 'bootstrap/js/bootstrap-buttons.js', 'jquery')
		->add('bootstrap', 'bootstrap/js/bootstrap-dropdown.js', 'jquery');

	$view->nest('footer', 'shophub::partials.footer');
});