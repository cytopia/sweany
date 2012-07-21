	<?php echo $form->start('form_login'); ?>
		<?php echo $form->fieldSetStart('Einloggen'); ?>
			<table style="border: 0px solid black; margin-left:70px; padding:10px;">
				<tbody>
					<tr>
						<td colspan="3"><?php echo $form->getError('username'); ?></td>
					</tr>
					<tr>
						<td>Benutzername</td>
						<td>Passwort</td>
						<td></td>
					</tr>
					<tr>
						<td><?php echo $form->inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
						<td><?php echo $form->passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
						<td><?php echo $form->submitButton('login_submit', 'Log In'); ?></td>
					</tr>
					<tr>
						<td></td>
						<td><span style="font-size:11px;"><?php echo Html::l('Passwort vergessen?', 'Users', 'passwortVergessen'); ?></span></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		<?php echo $form->fieldSetEnd(); ?>
	<?php echo $form->end(); ?>
	<br/><br/><br/>


	<?php echo $form->start('form_register'); ?>
		<?php echo $form->fieldSetStart('Jetzt kostenlos anmelden'); ?>
			<table style="padding:10px;">
				<tbody>
					<tr style="height:35px;">
						<td width="120">Benutzername:</td>
						<td width="150"><?php echo $form->inputField('username', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
						<td><?php echo $form->getError('username'); ?></td>
					</tr>
					<tr style="height:35px;">
						<td>Email:</td>
						<td><?php echo $form->inputField('email', NULL, array('size' => 20, 'maxlength' => '50')); ?></td>
						<td><?php echo $form->getError('email'); ?></td>
					</tr>
					<tr style="height:35px;">
						<td>Passwort:</td>
						<td><?php echo $form->passwordField('password', array('size' => 20, 'maxlength' => '50')); ?></td>
						<td><?php echo $form->getError('password'); ?></td>
					</tr>
					<tr style="height:35px;">
						<td>Nutzungsbedingungen:</td>
						<td colspan="2">
							<?php echo $form->getError('agb'); ?>
							<?php echo $form->checkBox('agb', '1', false); ?> Hiermit stimme ich der <a href="/Site/agb#datenschutz">Datenschutzerkl&auml;rung</a> und den <a href="/Site/agb#nutzungsbedingungen">Nutzungsbedingungen</a> zu.
						</td>
					</tr>
					<tr style="height:35px;">
						<td></td>
						<td><br/><?php echo $form->submitButton('register_submit', 'registrieren'); ?></td>
					</tr>
				</tbody>
			</table>
		<?php echo $form->fieldSetEnd().BR; ?>
	<?php echo $form->end(); ?>