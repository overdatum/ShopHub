<div id="main">
	<div class="page-header">
		<h1>Are you sure?</h1>
	</div>
	<div class="well">
		You are about to delete the account for "<?= $account->name . ' ('.$account->email.')' ?>". <b>If you do, there is no turning back!</b>
	</div>
	<?= Form::open('backend/accounts/delete/'.$account->uuid, 'PUT') ?>
		<?= Form::actions(array(Form::submit('Delete account', array('class' => 'btn large danger')), ' &nbsp; '.HTML::link('admin/accounts', 'Nope, I changed my mind', array('class' => 'btn large')))) ?>
	<?= Form::close() ?>
</div>