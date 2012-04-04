<html>
<head>
	<title>ES Demo - Users</title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<?= Asset::container('header')->styles() ?>
	<?= Asset::container('header')->scripts() ?>
</head>
<body>
	<div id="container">
		<h1>Users</h1>
		<h2><?= Session::has('message') ? Session::get('message') : '' ?></h2>
		<table>
			<thead>
				<tr>
					<th>First name</th>
					<th>Last name</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
				<?php $i = 1; foreach($users as $user): $i++; ?>
				<tr<?= ($i % 2 == 0 ? ' class="odd"' : '') ?>>
					<td><?= $user->first_name ?></td>
					<td><?= $user->last_name ?></td>
					<td>
						<?= HTML::link('demo/users/edit/'.$user->uuid, 'Edit user') ?> |
						<?= HTML::link('demo/users/delete/'.$user->uuid, 'Delete user') ?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
		<?= HTML::link('demo/users/add', 'Add user') ?>
	</div>
	<?= Asset::container('footer')->scripts() ?>
</body>
</html>