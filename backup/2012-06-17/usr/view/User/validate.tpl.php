
<?php if ($success):?>
	<h2>Benutzer Konto aktiviert</h2>

	<p>Dein Konto wurde erfolgreich aktiviert. Du kannst dich jetzt <?php echo Link::userLogin('hier'); ?> einloggen.</p>
<?php else: ?>
	<h2>Ung&uuml;ltiger Aktivierungskey</h2>

	<p>Der angegebene Aktivierungsschl&uuml;ssel ist ung&uuml;ltig oder bereits aktiviert.</p>
<?php endif; ?>
