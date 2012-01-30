<div id="main">
	<?= Form::open('admin/accounts/add', 'POST', array('class' => 'form-horizontal')) ?>
		<?= Form::field('text', 'name', 'Name', array(Input::old('name')), array('error' => $errors->first('name'))) ?>
		<?= Form::field('text', 'email', 'E-mail address', array(Input::old('email')), array('error' => $errors->first('email'))) ?>
		<?= Form::field('password', 'password', 'Password', array(), array('error' => $errors->first('password'))) ?>
		<?= Form::actions(array(Form::submit('Add account', array('class' => 'btn large primary')))) ?>
	<?= Form::close() ?>
</div>