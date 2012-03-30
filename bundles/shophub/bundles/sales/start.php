<?php

Route::controller(array(
	'sales::backend.customers',
	'sales::leaderboard',
), '(:controller)/(:wildcards)', 'index');