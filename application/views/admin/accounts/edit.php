<div id="main">
	<?= Form::open('admin/accounts/edit/'.$account->id, 'PUT') ?>
		<?= Form::field('text', 'name', 'Name', array(Input::old('name', $account->name)), array('error' => $errors->first('name'))) ?>
		<?= Form::field('text', 'email', 'E-mail address', array(Input::old('email', $account->email)), array('error' => $errors->first('email'))) ?>
		<?= Form::field('text', 'password', 'New password', array(), array('error' => $errors->first('password'))) ?>
		<?= Form::actions(array(Form::submit('Edit account', array('class' => 'btn large primary')))) ?>
	<?= Form::close() ?>
</div>