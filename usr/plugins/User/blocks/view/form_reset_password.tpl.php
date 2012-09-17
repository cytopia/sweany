<?php echo Form::start('block_reset_password_form'); ?>
	<table>
		<tbody>
			<tr>
				<td><?php echo Form::getError('password1'); ?></td>
			</tr>
			<tr>
				<td><?php echo $language->newPassword;?></td>
				<td>
					<?php echo Form::passwordField('password1'); ?>
				</td>
			</tr>
			<tr>
				<td><?php echo $language->newPasswordRepeat;?></td>
				<td>
					<?php echo Form::passwordField('password2'); ?>
				</td>
			</tr>
			<tr>
				<td></td>
				<td><?php echo Form::submitButton('edit', $language->create); ?></td>
			</tr>
		</tbody>
	</table>
<?php echo Form::end();?>