<?php if ( isset($user) ):?>
	<?php if ( $user->isAdmin() ) :?>
		<a href="/Backend/Login/logout">logout</a> |
		<a href="/Backend/Overview/start">start</a> |
		<a href="/Backend/Visitors/lastVisits">last visits</a> |
		<a href="/Backend/Contact/show">Contacts</a> |
		<a href="/Backend/Overview/serverInfo">phpinfo</a>
	<?php endif; ?>
<?php endif; ?>