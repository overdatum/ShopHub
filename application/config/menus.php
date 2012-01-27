<?php
return array(
	'admin' => array(
		'admin/dashboard' => array(
			'name' => 'Dashboard'
		),
		'admin/accounts' => array(
			'name' => 'Accounts',
		)
	),
	'frontend' => array(
		'home' => array(
			'name' => 'Home'
		)
	),
	'logged_in' => array(
		'frontend' => array(
			'account/profile' => array(
				'name' => 'Profile'
			),
			'account/logout' => array(
				'name' => 'Uitloggen'
			)
		),
	),
	'logged_out' => array(
		'frontend' => array(
			'signup' => array(
				'name' => '<b>Sign up</b>'
			),
			'account/login' => array(
				'name' => '<b>Login</b>'
			)
		)
	)
);