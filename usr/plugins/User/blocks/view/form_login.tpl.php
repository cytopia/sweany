<?php echo Form::start('block_form_login'); ?>
	<?php echo Form::fieldSetStart($language->login); ?>
		<table style="border: 0px solid black; margin-left:70px; padding:10px;">
			<tbody>
				<tr>
					<td colspan="3"><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr>
					<td><?php echo $language->username; ?></td>
					<td><?php echo $language->password; ?></td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo Form::inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::submitButton('login_submit', 'Log In'); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><span style="font-size:11px;"><?php echo Html::l($language->lostPassword, 'User', 'lostPassword'); ?></span></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd(); ?>
<?php echo Form::end(); ?>
