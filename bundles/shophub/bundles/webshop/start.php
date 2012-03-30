<?php

Route::controller(array(
	'webshop::backend.webshops',
	'webshop::backend.products',
	'webshop::product',
), '(:controller)/(:wildcards)', 'index');