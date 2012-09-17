<?php echo Form::start('block_form_register'); ?>
	<?php echo Form::fieldSetStart($language->registerForFree); ?>
		<table style="padding:10px;">
			<tbody>
				<tr style="height:35px;">
					<td width="120"><?php echo $language->username;?>:</td>
					<td width="150"><?php echo Form::inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><?php echo $language->email;?></td>
					<td><?php echo Form::inputField('email', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('email'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><?php echo $language->password;?></td>
					<td><?php echo Form::passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('password'); ?></td>
				</tr>
				<?php if ( Config::get('acceptTermsOnRegister', 'user') ):?>
				<tr style="height:35px;">
					<td><?php echo $language->terms;?></td>
					<td colspan="2">
						<?php echo Form::getError('terms'); ?>
						<?php echo Form::checkBox('terms', '1', false); ?> <?php echo sprintf($language->IHerebyAccept, '<a href="'.Config::get('policyUrl', 'user').'">'.$language->dataPolicy.'</a>', '<a href="'.Config::get('termsUrl', 'user').'">'.$language->terms.'</a>');?>
					</td>
				</tr>
				<?php endif; ?>
				<tr style="height:35px;">
					<td></td>
					<td><br/><?php echo Form::submitButton('register_submit', $language->register); ?></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd().BR; ?>
<?php echo Form::end(); ?>