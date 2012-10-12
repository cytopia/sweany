<h1>Test Page</h1><?phpecho Form::start('form_test');
	echo Form::fieldSetStart($language->fieldsetName);

		echo Form::checkBox('is_checked', '1', true);
		echo Form::label('is_checked', 'checkbox');
		echo '<br/><br/>';		echo Form::radioButton('test_radio', '1', true);
		echo Form::label('test_radio', 'this');		echo '<br/>';
		echo Form::radioButton('test_radio', '2', true);		echo Form::label('test_radio', 'or that');		echo '<br/><br/>';
		echo Form::selectBox('select_id', $selectBoxData);
		echo Form::getError('select_id');
		echo Form::label('select_id', 'select box');
		echo '<br/><br/>';

		echo Form::getError('test_input');
		echo Form::label('test_input', 'input');
		echo Form::inputField('test_input');		echo '<br/><br/>';		echo Form::submitButton('form_submit', 'submit button');
	echo Form::fieldSetEnd();echo Form::end();if (Form::isSubmitted('form_test')){	debug($_POST);}