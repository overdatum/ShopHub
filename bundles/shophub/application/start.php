<?php

Bundle::register('authority', array(
	'auto' => true,
	'location' => 'thirdparty/authority'
));

Bundle::start('authority');

Bundle::register('anbu', array(
	'auto' => true,
	'location' => 'thirdparty/anbu'
));

Bundle::start('anbu');


$bundles = new FilesystemIterator(__DIR__ . DS . '..' . DS . 'bundles', FilesystemIterator::SKIP_DOTS);

foreach ($bundles as $bundle)
{
	if ($bundle->isDir() && file_exists(__DIR__ . DS . '..' . DS . 'bundles' . DS . $bundle->getFilename() . DS . 'start.php'))
	{
		Bundle::register($bundle->getFilename(), array(
			'auto' => true,
			'location' => 'shophub' . DS . 'bundles' . DS . $bundle->getFilename()
		));

		Bundle::start($bundle->getFilename());
	}
}

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('auth/login');
});

Filter::register('can', function($action, $resource)
{
	if ( ! Authority::can($action, $resource)) return Redirect::to('home');
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