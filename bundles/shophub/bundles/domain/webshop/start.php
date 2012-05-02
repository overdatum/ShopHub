<?php

Route::controller(array(
	'shophub_webshop::backend.webshops',
	'shophub_webshop::backend.products',
	'shophub_webshop::product',
), '(:controller)/(:wildcards)', 'index');