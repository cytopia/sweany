<div class="plugin_user_edit_data_form">
	<?php
	echo Form::start('form_edit_data');
		?>
		<div class="plugin_user_edit_data_fields">
			<?php
			echo Form::getError('old_password');
			echo Form::label('old_password', $language->currentPassword);
			echo Form::passwordField('old_password').'<br/>';
			?>
		</div>
		<div class="plugin_user_edit_data_fieldset_password">
			<?php
			echo Form::fieldSetStart($language->changePassword);
				echo Form::getError('new_password1');
				echo Form::label('new_password1', $language->newPassword);
				echo Form::passwordField('new_password1').'<br/>';

				echo Form::getError('new_password2');
				echo Form::label('new_password2', $language->repeatNewPassword);
				echo Form::passwordField('new_password2');
			echo Form::fieldSetEnd().'<br/>';
			?>
		</div>
		<div class="plugin_user_edit_data_fieldset_email">
			<?php
			echo Form::fieldSetStart($language->changeEmail);
				echo Form::getError('email1');
				echo Form::label('email1', $language->newEmail);
				echo Form::inputField('email1', $email).'<br/>';

				echo Form::getError('email2');
				echo Form::label('email2', $language->repeatNewEmail);
				echo Form::inputField('email2', $email);
			echo Form::fieldSetEnd();
			?>
		</div>
		<?php
		echo Form::submitButton('edit', $language->edit);

	echo Form::end();
	?>
</div>