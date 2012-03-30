<?php

Route::controller(array(
	'stores::backend.pages',
	'stores::backend.stores',
	'stores::contact',
), '(:controller)/(:wildcards)', 'index');