<?php
	$diabled = true;

	// Check if registration has been disabled
	if ($disabled)
	{
		$options	= array('disabled' => 'disabled');
		$class		= 'userRegistrationFormDisabled';
	}
	else
	{
		$options = array();
		$class		= 'userRegistrationFormEnabled';
	}
?>

<?php echo Form::start('block_form_register'); ?>
	<?php echo Form::fieldSetStart($language->registerForFree); ?>
		<?php if ($disabled) { echo '<strong>'.$language->registrationDisabled.'</strong><br/><br/>'; } ?>
		<table style="padding:10px;">
			<tbody>
				<tr style="height:35px;">
					<td width="120"><span class="<?php echo $class;?>"><?php echo $language->username;?>:</span></td>
					<td width="150"><?php echo Form::inputField('username', NULL, $options); ?></td>
					<td><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><span class="<?php echo $class;?>"><?php echo $language->email;?>:</span></td>
					<td><?php echo Form::inputField('email', NULL, $options); ?></td>
					<td><?php echo Form::getError('email'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><span class="<?php echo $class;?>"><?php echo $language->password;?>:</span></td>
					<td><?php echo Form::passwordField('password', $options); ?></td>
					<td><?php echo Form::getError('password'); ?></td>
				</tr>
				<?php if ( Config::get('acceptTermsOnRegister', 'user') ):?>
				<tr style="height:35px;">
					<td><span class="<?php echo $class;?>"><?php echo $language->terms;?>:</span></td>
					<td colspan="2">
						<?php echo Form::getError('terms'); ?>
						<?php echo Form::checkBox('terms', '1', false, $options); ?> <span class="<?php echo $class;?>"><?php echo sprintf($language->IHerebyAccept, '<a href="'.Config::get('policyUrl', 'user').'">'.$language->dataPolicy.'</a>', '<a href="'.Config::get('termsUrl', 'user').'">'.$language->terms.'</a>');?></span>
					</td>
				</tr>
				<?php endif; ?>
				<?php if (!$disabled) : ?>
					<tr style="height:35px;">
						<td></td>
						<td><br/><?php echo Form::submitButton('register_submit', $language->register, $options); ?></td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd().BR; ?>
<?php echo Form::end(); ?>