<div class="adm_contact">
	<table class="adm_contact">
		<thead>
			<tr>
				<th>Sys User Id</th>
				<td><?php echo $message->fk_user_id;?></td>
			</tr>
			<tr>
				<th>Sys User Name</th>
				<td><?php echo $message->username;?></td>
			</tr>
			<tr>
				<th>Referer</th>
				<td><?php echo $message->referer;?></td>
			</tr>
			<tr>
				<th>Useragent</th>
				<td><?php echo $message->useragent;?></td>
			</tr>
			<tr>
				<th>IP</th>
				<td><?php echo $message->ip;?></td>
			</tr>
			<tr>
				<th>Host</th>
				<td><?php echo $message->host;?></td>
			</tr>
			<tr>
				<th colspan="2">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Contact Id</th>
				<td><?php echo $message->id;?></td>
			</tr>
			<tr>
				<th>Received</th>
				<td><?php echo TimeHelper::getFormattedDate('d.m.Y \a\t H:i', $message->created);?></td>
			</tr>
			<tr>
				<th>Name</th>
				<td><?php echo $message->name;?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo $message->email;?></td>
			</tr>
			<tr>
				<th>Subject</th>
				<td><?php echo $message->subject;?></td>
			</tr>
			<tr>
				<th colspan="2">Message</th>
			</tr>
			<tr>
				<td colspan="2"><pre><?php echo $message->message;?></pre></td>
			</tr>

		</tbody>
	</table>
</div>