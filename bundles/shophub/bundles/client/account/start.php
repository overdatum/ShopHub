<?php

Route::controller(array(
	'shophub_client_account::backend.accounts',
	'shophub_client_account::auth',
	'shophub_client_account::profile'
), '(:controller)/(:wildcards)', 'index');

Menu::container(array('admin', 'webshop'), 'backend')
	->add('accounts', __('shophub::menu.backend.accounts'));

Menu::add('auth', __('shopbhub::auth'))
	->add('profile', __('shophub::menu.profile'));