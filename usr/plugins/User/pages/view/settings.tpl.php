<div class="plugin_user_edit_data_form">
	<?php
	echo Form::start('form_edit_settings');

		echo Form::fieldSetStart($language->regionalSettings);
			echo Form::getError('timezones');
			echo Form::label('timezones', $language->timezone);
			echo Form::selectBox('timezones', $timezones, $def_zone).'<br/>';

			echo Form::getError('languages');
			echo Form::label('languages', $language->language);
			echo Form::selectBox('languages', $languages, $def_lang);
		echo Form::fieldSetEnd().'<br/>';

		echo Form::submitButton('edit', $language->edit);

	echo Form::end();
	?>
</div>