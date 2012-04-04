<html>
<head>
	<title>ES Demo - Users</title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<?= Asset::container('header')->styles() ?>
	<?= Asset::container('header')->scripts() ?>
</head>
<body>
	<div id="container">
		<h1>Edit User</h1>
		<?= Form::open('demo/users/edit', 'PUT') ?>
			<input type="hidden" name="uuid" value="<?= $user->uuid ?>">
			<label for="first_name">First name</label>
			<input type="text" name="first_name" value="<?= $user->first_name ?>"><br>
			<label for="first_name">Last name</label>
			<input type="text" name="last_name" value="<?= $user->last_name ?>">
			<input type="submit" value="Save changes">
		<?= Form::close() ?>
	</div>
	<?= Asset::container('footer')->scripts() ?>
</body>
</html>