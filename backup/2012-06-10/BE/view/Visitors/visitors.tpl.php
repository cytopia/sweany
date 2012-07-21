<h1><?php echo $headline; ?></h1>
<?php echo $sessCount[0]['count']; ?> unique visitors by session<br/>
<?php echo $hostCount[0]['count']; ?> unique visitors by host<br/><br/>

Showing <?php echo $count; ?> rows of <?php echo $total; ?>
<table>
	<tr>
		<th>id</th>
		<th>time</th>
		<th>url</th>
		<th>referrer</th>
		<th>useragent</th>
		<th>ip</th>
		<th>host</th>
		<th>session_id</th>
	</tr>
	<?php foreach($visitors as $visitor): ?>
		<?php 
		
			$referrer = rawurldecode(rawurldecode($visitor['referer']));
			$useragent=$visitor['useragent'];
			$sess_link		= '<a href="/Backend/Visitors/showUniqueSession/'.$visitor['session_id'].'">'.$visitor['session_id'].'</a>';
			$host_link		= '<a href="/Backend/Visitors/showUniqueHost/'.$visitor['host'].'">'.$visitor['host'].'</a>';
			?>
		<tr>
			<td><?php echo $visitor['id']; ?></td>
			<td><?php echo $visitor['created']; ?></td>
			<td><?php echo $visitor['url']; ?></td>
			<td><?php echo $referrer; ?></td>
			<td><?php echo $useragent; ?></td>
			<td><?php echo $visitor['ip']; ?></td>
			<td><?php echo $host_link; ?></td>
			<td><?php echo $sess_link; ?></td>
		</tr>
	<?php endforeach; ?>
</table><br />
