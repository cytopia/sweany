<h1><?php echo $headline; ?></h1>


<h2>Last Login Info</h2>
<table>
	<tr>
		<th>last ip</th><td><?php echo $info['last_ip']; ?></td>
	</tr>
	<tr>
		<th>last host</th><td><?php echo $info['last_host']; ?></td>
	</tr>
	<tr>
		<th>last time</th><td><?php echo $info['last_login']; ?></td>
	</tr>
	<tr>
		<th>failed logins</th><td><?php echo $info['failed_logins']; ?></td>
	</tr>
</table><br/>

<h2>Server Time</h2>
<table>
	<tr>
		<th>php</th><th>strftime REQUEST_TIME</th><td><?php echo strftime("%Y-%m-%d %H:%M:%S",$_SERVER['REQUEST_TIME']); ?></td>
	</tr>
	<tr>
		<th>php</th><th>strftime time()</th><td><?php echo strftime("%Y-%m-%d %H:%M:%S",time()); ?></td>
	</tr>
	<tr>
		<th>php</th><th>date REQUEST TIME</th><td><?php echo date("Y-m-d H:i:s",$_SERVER['REQUEST_TIME']); ?></td>
	</tr>
	<tr>
		<th>php</th><th>date time()</th><td><?php echo date("Y-m-d H:i:s",time()); ?></td>
	</tr>
	<tr>
		<th>php</th><th>timezone</th><td><?php echo date_default_timezone_get(); ?></td>
	</tr>
	<tr>
		<th>php</th><th>GMT offset</th><td><?php echo 'GMT '.date("P", time()); ?></td>
	</tr>
	<tr>
		<th>mysql</th><th>Global NOW()</th><td><?php echo MySql::$srvTime; ?></td>
	</tr>
	<tr>
		<th>mysql</th><th>Session NOW()</th><td><?php echo MySql::_getNow(); ?></td>
	</tr>
	<tr>
		<th>mysql</th><th>Global/Session offset</th><td><?php echo MySql::$srvTimeOff; ?></td>
	</tr>
	<tr>
		<th>mysql</th><th>global timezone</th><td><?php echo MySql::_getGlobalTimeZone(); ?></td>
	</tr>
	<tr>
		<th>mysql</th><th>GMT session offset</th><td>GMT <?php echo MySql::_getSessionTimeZone(); ?></td>
	</tr>
</table><br/>

<h2>Visitors</h2>
<table>
	<tr>
		<th>online</th><td><?php echo $online; ?></td>
	</tr>
	<tr>
		<th>page Hits</th><td><?php echo $pageHits; ?></td>
	</tr>
	<tr>
		<th>unique by session</th><td><?php echo $sessCount; ?></td>
	</tr>
	<tr>
		<th>unique by host</th><td><?php echo $hostCount; ?></td>
	</tr>
</table><br/>

<h2>System Users</h2>
<table>
	<tr>
		<th>id</th>
		<th>username</th>
		<th>email</th>
		<th>admin</th>
		<th>enabled</th>
		<th>locked</th>
		<th>deleted</th>
		<th>last ip</th>
		<th>last host</th>
		<th>last login</th>
		<th>failed login count</th>
	</tr>
	<?php foreach($users as $user): ?>
		<tr>
			<td><?php echo $user['id']; ?></td>
			<td><?php echo $user['username']; ?></td>
			<td><?php echo $user['email']; ?></td>
			<td><?php echo $user['is_admin']; ?></td>
			<td><?php echo $user['is_enabled']; ?></td>
			<td><?php echo $user['is_locked']; ?></td>
			<td><?php echo $user['is_deleted']; ?></td>
			<td><?php echo $user['last_ip']; ?></td>
			<td><?php echo $user['last_host']; ?></td>
			<td><?php echo $user['last_login']; ?></td>
			<td><?php echo $user['last_failed_login_count']; ?></td>
		</tr>
	<?php endforeach; ?>
</table><br />

<h2>Failed Logins</h2>
<table>
	<tr>
		<th>username</th>
		<th>ip</th>
		<th>hostname</th>
		<th>time</th>
		<th>referer</th>
		<th>useragent</th>
		<th>session id</th>
	</tr>
	<?php foreach ($failedLogins as $login): ?>
		<tr>
			<td><?php echo $login['username']; ?></td>
			<td><?php echo $login['ip']; ?></td>
			<td><?php echo $login['hostname']; ?></td>
			<td><?php echo $login['created']; ?></td>
			<td><?php echo $login['referer']; ?></td>
			<td><?php echo $login['useragent']; ?></td>
			<td><?php echo $login['session_id']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>