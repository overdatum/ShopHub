<div id="main">
	<div class="page-header">
		<div class="pull-right">
			<?= Form::open('admin/accounts', 'GET') ?>
				<?= Form::input('text', 'q', Input::get('q')) ?> &nbsp;
				<button type="submit" class="btn primary"><i class="icon search"></i></button>
			<?= Form::close() ?>
		</div>
		<h1>Accounts</h1>
	</div>
	<?php Notification::show() ?>
	<?php if(count($accounts->results) > 0): ?>
		<table class="table table-striped">
			<thead>
				<tr>
					<th><?= HTML::sort_link('admin/accounts', 'name', 'Name') ?></th>
					<th><?= HTML::sort_link('admin/accounts', 'email', 'Email') ?></th>
					<th>Roles</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($accounts->results as $account): ?>
				<tr>
					<td>
						<h2><?= $account->name ?></h2>
					</td>
					<td>
						<?= $account->email ?>
					</td>
					<td>
						<?php
						foreach($account->roles as $role)
						{

							echo '<b>'.$roles_lang[$role->id]->name.'</b><br>';
						}
						?>
					</td>
					<td width="120" style="text-align:right">
						<?= HTML::link('admin/accounts/edit/'.$account->id, '<i class="icon pencil"></i>', array('class' => 'btn small')) ?>
						<?php echo Authority::can('delete', 'Account', $account) ? '&nbsp; '.HTML::link('admin/accounts/delete/'.$account->id, 'Delete', array('class' => 'btn danger')) : ''; ?>
					</td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
		<div class="pull-left">
			<?= $accounts->links() ?>
		</div>
	<?php else: ?>
		<div class="well">
			Er zijn geen accounts gevonden...
		</div>
	<?php endif ?>
	<div class="pull-right">
		<?= HTML::link('admin/accounts/add', 'Add account', array('class' => 'btn large primary')) ?>
	</div>
</div>