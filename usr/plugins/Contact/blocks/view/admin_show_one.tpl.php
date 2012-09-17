<div class="adm_contact">
	<table class="adm_contact">
		<tbody>
			<tr>
				<th>Id</th>
				<td><?php echo $message['id'];?></td>
			</tr>
			<tr>
				<th>Received</th>
				<td><?php echo TimeHelper::getFormattedDate($message['created'], 'd.m.Y \a\t H:i');?></td>
			</tr>
			<tr>
				<th>Name</th>
				<td><?php echo $message['name'];?></td>
			</tr>
			<tr>
				<th>Email</th>
				<td><?php echo $message['email'];?></td>
			</tr>
			<tr>
				<th>Subject</th>
				<td><?php echo $message['subject'];?></td>
			</tr>
			<tr>
				<th colspan="2">Message</th>
			</tr>
			<tr>
				<td colspan="2"><?php echo $message['message'];?></td>
			</tr>

		</tbody>
	</table>
</div>