<h1>Login</h1>

<?php
echo $form->start('form_login');

	echo $form->fieldSetStart('Login form');


		echo $form->getError('notice');
		echo $form->label('username', 'User Name');
		echo $form->inputField('username', NULL, array('size' => 20)).BR.BR;

		echo $form->label('password', 'Password');
		echo $form->passwordField('password', array('size' => 20)).BR.BR;

	echo $form->fieldSetEnd().BR;

	echo $form->submitButton('login_submit', 'login');
	
echo $form->end();
?>