<?php

Route::controller(array(
	'account::backend.accounts',
	'account::auth',
	'account::profile'
), '(:controller)/(:wildcards)', 'index');