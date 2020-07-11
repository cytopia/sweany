<?php echo Form::start('block_form_lost_password'); ?>
	<?php echo Form::fieldSetStart($language->resetPassword); ?>
		<table style="border: 0px solid black; margin-left:10px; padding:10px;">
			<tbody>
				<tr>
					<td colspan="2"><?php echo Form::getError('email'); ?></td>
				</tr>
				<tr>
					<td><?php echo $language->yourEmail;?></td>
					<td><?php echo Form::inputField('email', NULL, array('size' => 30, 'maxlength' => '50')); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?php echo Form::submitButton('login_submit', $language->resetPassword); ?></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd(); ?>
<?php echo Form::end(); ?>
