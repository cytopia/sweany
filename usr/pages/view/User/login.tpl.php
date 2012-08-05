<?php echo Form::start('form_login'); ?>
	<?php echo Form::fieldSetStart('Einloggen'); ?>
		<table style="border: 0px solid black; margin-left:70px; padding:10px;">
			<tbody>
				<tr>
					<td colspan="3"><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr>
					<td>Benutzername</td>
					<td>Passwort</td>
					<td></td>
				</tr>
				<tr>
					<td><?php echo Form::inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::submitButton('login_submit', 'Log In'); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><span style="font-size:11px;"><?php echo Html::l('Passwort vergessen?', 'Users', 'passwortVergessen'); ?></span></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd(); ?>
<?php echo Form::end(); ?>
<br/><br/><br/>


<?php echo Form::start('form_register'); ?>
	<?php echo Form::fieldSetStart('Jetzt kostenlos anmelden'); ?>
		<table style="padding:10px;">
			<tbody>
				<tr style="height:35px;">
					<td width="120">Benutzername:</td>
					<td width="150"><?php echo Form::inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('username'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td>Email:</td>
					<td><?php echo Form::inputField('email', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('email'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td>Passwort:</td>
					<td><?php echo Form::passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
					<td><?php echo Form::getError('password'); ?></td>
				</tr>
				<tr style="height:35px;">
					<td>Nutzungsbedingungen:</td>
					<td colspan="2">
						<?php echo Form::getError('agb'); ?>
						<?php echo Form::checkBox('agb', '1', false); ?> Hiermit stimme ich der <a href="/Site/agb#datenschutz">Datenschutzerkl&auml;rung</a> und den <a href="/Site/agb#nutzungsbedingungen">Nutzungsbedingungen</a> zu.
					</td>
				</tr>
				<tr style="height:35px;">
					<td></td>
					<td><br/><?php echo Form::submitButton('register_submit', 'registrieren'); ?></td>
				</tr>
			</tbody>
		</table>
	<?php echo Form::fieldSetEnd().BR; ?>
<?php echo Form::end(); ?>