<html>
<head>
	<title>ES Demo - Users</title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<?= Asset::container('header')->styles() ?>
	<?= Asset::container('header')->scripts() ?>
</head>
<body>
	<div id="container">
		<h1>Delete user</h1>
		Are you sure you want to delete "<?= $user->first_name . ' ' . $user->last_name ?>" from the system?
		<?= Form::open('demo/users/delete', 'PUT') ?>
			<input type="hidden" name="uuid" value="<?= $user->uuid ?>">
			<input type="submit" value="Yes, go ahead!">
		<?= Form::close() ?>
	</div>
	<?= Asset::container('footer')->scripts() ?>
</body>
</html>