<?php

Route::controller(array(
	'account::backend.accounts',
	'account::auth',
	'account::profile'
), 'this/is/cool/(:any)/(:controller)/(:wildcards)', 'index');