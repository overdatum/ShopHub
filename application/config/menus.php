<?php
return array(
	'admin' => array(
		'backend/dashboard' => array(
			'name' => 'Dashboard'
		),
		'backend/accounts' => array(
			'name' => 'Accounts'
		)
	),
	'frontend' => array(
		'home' => array(
			'name' => 'Home'
		)
	),
	'logged_in' => array(
		'frontend' => array(
			'profile' => array(
				'name' => 'Profile'
			),
			'auth/logout' => array(
				'name' => 'Uitloggen'
			)
		),
	),
	'logged_out' => array(
		'frontend' => array(
			'signup' => array(
				'name' => '<b>Sign up</b>'
			),
			'auth/login' => array(
				'name' => '<b>Login</b>'
			)
		)
	)
);