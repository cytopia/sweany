<h2>Erfolgreich registriert</h2>

<p>Um dein Konto freizuschalten musst du noch den Best&auml;tigungslink aufrufen, den wir dir per Email gesendet haben.</p>
<h3>Daten</h3>
<table>
	<tr>
		<td>Benutername</td><td><?php echo $username; ?></td>
	</tr>
	<tr>
		<td>Email</td><td><?php echo $email; ?></td>
	</tr>
</table>

<p>Du kannst dich jetzt <?php echo Link::userLogin('hier einloggen'); ?></p>
<?php echo Html::l('temp Link to validate', 'User', 'validate', array($user_data['validation_key'])); ?>
