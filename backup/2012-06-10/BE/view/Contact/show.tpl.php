<h1>Contacts</h1>


<br/><br/>
<table>
	<tr>
		<th>id</th>
		<th>name</th>
		<th>email</th>
		<th>ip</th>
		<th>subject</th>
		<th>message</th>
		<th>date</th>
	</tr>
	<?php foreach ($contacts as $contact): ?>
		<tr>
			<td><?php echo $contact['id']; ?></td>
			<td><?php echo $contact['name']; ?></td>
			<td><?php echo $contact['email']; ?></td>
			<td><?php echo $contact['id']; ?></td>
			<td><?php echo $contact['subject']; ?></td>
			<td><?php echo $contact['message']; ?></td>
			<td><?php echo $contact['created']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>