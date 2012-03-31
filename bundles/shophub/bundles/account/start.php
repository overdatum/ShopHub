<?php

Route::controller(array(
	'account::backend.accounts',
	'account::auth',
	'account::profile'
), '(:controller)/(:wildcards)', 'index');

Menu::container('backend', true)
	->add('accounts', __('shophub::menu.backend.accounts'));

Menu::add('auth', __('shopbhub::auth'))
	->add('profile', __('shophub::menu.profile'));