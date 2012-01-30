<div id="main">
	<?= Form::open('admin/accounts/edit/'.$account->id, 'PUT', array('class' => 'form-horizontal')) ?>
		<?= Form::field('text', 'name', 'Name', array(Input::old('name', $account->name)), array('error' => $errors->first('name'))) ?>
		<?= Form::field('text', 'email', 'E-mail address', array(Input::old('email', $account->email)), array('error' => $errors->first('email'))) ?>
		<?= Form::field('text', 'password', 'New password', array(), array('error' => $errors->first('password'))) ?>
		<?= Form::field('select', 'role_ids[]', 'Groups', array($roles, $active_roles, array('multiple' => 'multiple')), array('error' => $errors->first('password'))) ?>
		<?= Form::actions(array(Form::submit('Save changes', array('class' => 'btn large primary')))) ?>
	<?= Form::close() ?>
</div>