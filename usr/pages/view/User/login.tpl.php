<?php echo Form::start('form_login'); ?>
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
					<td><span style="font-size:11px;"><?php echo Html::l($language->lostPassword, 'Users', 'passwortVergessen'); ?></span></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd(); ?>
<?php echo Form::end(); ?>
<br/><br/><br/>


<?php echo Form::start('form_register'); ?>
	<?php echo Form::fieldSetStart($language->registerFree); ?>
		<table style="padding:10px;">
			<tbody>
				<tr style="height:35px;">
					<td width="120"><?php echo $language->username; ?>:</td>
					<td width="150"><?php echo Form::inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><?php echo $language->email; ?>:</td>
					<td><?php echo Form::inputField('email', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('email'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><?php echo $language->password; ?>:</td>
					<td><?php echo Form::passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('password'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td><?php echo $language->tos; ?>:</td>
					<td colspan="2">
						<?php echo Form::getError('agb'); ?>
						<?php echo Form::checkBox('agb', '1', false); ?> <?php echo sprintf($language->acceptTos, '<a href="/Site/agb#datenschutz">'.$language->dataPolicy.'</a>', '<a href="/Site/agb#nutzungsbedingungen">'.$language->tos.'</a>'); ?>
					</td>
				</tr>
				<tr style="height:35px;">
					<td></td>
					<td><br/><?php echo Form::submitButton('register_submit', $language->register); ?></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd().BR; ?>
<?php echo Form::end(); ?>