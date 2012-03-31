<?php

Route::controller(array(
	'store::backend.pages',
	'store::backend.stores',
	'stores::contact',
), '(:controller)/(:wildcards)', 'index');

Menu::container('backend.store', true)
	->add('pages', __('shophub::menu.backend.pages'));

Menu::container('frontend.store')->add('auth', __('shopbhub::auth'))
	->add('profile', __('shophub::menu.profile'));