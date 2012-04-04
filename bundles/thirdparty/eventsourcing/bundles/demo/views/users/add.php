<html>
<head>
	<title>ES Demo - Users</title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<?= Asset::container('header')->styles() ?>
	<?= Asset::container('header')->scripts() ?>
</head>
<body>
	<div id="container">
		<h1>Add User</h1>
		<?= Form::open('demo/users/add', 'POST') ?>
			<label for="first_name">First name</label>
			<input type="text" name="first_name"><br>
			<label for="first_name">Last name</label>
			<input type="text" name="last_name">
			<input type="submit" value="Add user">
		<?= Form::close() ?>
	</div>
	<?= Asset::container('footer')->scripts() ?>
</body>
</html>