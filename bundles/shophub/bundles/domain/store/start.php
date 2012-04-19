<?php

Route::controller(array(
	'store::backend.pages',
	'store::backend.stores',
	'stores::contact',
), '(:controller)/(:wildcards)', 'index');

Menu::container(array('admin', 'store'), 'backend')
	->add('pages', __('shophub::menu.backend.pages'));

Menu::container('frontend')
	->add('auth/login', __('shopbhub::auth.login'))
	->add('auth/logout', __('shopbhub::auth.logout'))
	->add('profile', __('shophub::menu.profile'));