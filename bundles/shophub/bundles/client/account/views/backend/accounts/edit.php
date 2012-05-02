<div id="main">
	<div class="page-header">
		<h1>Update the account for "<?= $account->name ?>"</h1>
	</div>

	<?= Form::open('backend/accounts/edit/'.$account->uuid, 'PUT', array('class' => 'form-horizontal')) ?>
		<?= Form::field('text', 'name', 'Name', array(Input::old('name', $account->name)), array('error' => $errors->first('name'))) ?>
		<?= Form::field('text', 'email', 'E-mail address', array(Input::old('email', $account->email)), array('error' => $errors->first('email'))) ?>
		<?= Form::field('text', 'password', 'New password', array(), array('error' => $errors->first('password'))) ?>
		<?= Form::field('select', 'role_uuids[]', 'Roles', array($roles, $active_roles, array('multiple' => 'multiple')), array('error' => $errors->first('password'))) ?>
		<?= Form::field('select', 'language_uuid', 'Language', array($languages, array($account->language->uuid)), array('error' => $errors->first('language_uuid'))) ?>

		<?= Form::actions(array(Form::submit('Save changes', array('class' => 'btn large primary')))) ?>
	<?= Form::close() ?>
</div>