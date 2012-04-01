<?php

Route::controller(array(
	'sales::backend.customers',
	'sales::leaderboard',
), '(:controller)/(:wildcards)', 'index');

Menu::container(array('admin', 'sales'), 'backend')
	->add('customers', __('shophub::menu.backend.customers'));

Menu::add('leaderboard', __('shopbhub::menu.loaderboard'));


//echo Menu::container('admin');