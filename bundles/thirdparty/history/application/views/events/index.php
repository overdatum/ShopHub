<html>
<head>
	<title>ES - Events</title>
	<link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<?= Asset::container('header')->styles() ?>
	<?= Asset::container('header')->scripts() ?>
</head>
<body>
	<ul id="tabs">
		<li class="active">
			<a href="#">Events</a>
		</li>
		<li>
			<a href="#">EventHandlers</a>
		</li>
	</ul>
	<div id="container">
		<div class="tab active">
			<h2>Filter</h2>
			...<br>
			<br>
			<h2>Events</h2>
			<table>
				<thead>
					<tr>
						<th>Time</th>
						<th>Event</th>
						<th>UUID</th>
						<th>Version</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; foreach($events as $event): $i++; ?>
					<tr<?= ($i % 2 == 0 ? ' class="odd"' : '') ?>>
						<td><?= date('d-m-Y H:i:s', strtotime($event->executed_at)) ?></td>
						<td><?= get_class(unserialize($event->event)) ?></td>
						<td><?= $event->uuid ?></td>
						<td><?= $event->version ?></td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
			<br>
			<br>
			<h2>Replay</h2>
			<p>Replay the filtered events through a selection of handlers.</p>
			<table>
				<thead>
					<tr>
						<th>✓</th>
						<th>EventHandler</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($eventhandlers as $eventhandler): ?>
					<tr class="<?= $eventhandler['enabled'] ? 'enabled' : 'disabled' ?>">
						<td align="center">
							<input type="checkbox">
						</td>
						<td>
							<b><?= $eventhandler['title'] ?></b><br>
							<?= $eventhandler['description'] ?>
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<div class="tab">
			// 143 Events
			// Activated User Projector V1
			// 245 Events
			// Decativated User Projector V1
			// Activated User Projector V2
		</div>
	</div>
	<?= Asset::container('footer')->scripts() ?>
</body>
</html>