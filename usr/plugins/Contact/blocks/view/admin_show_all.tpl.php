<div class="adm_contacts">
	<table class="adm_contacts">
		<thead>
			<tr>
				<th>count</th>
				<th>id</th>
				<th>name</th>
				<th>subject</th>
				<th>received</th>
				<th>status</th>
			</tr>
		</thead>
		<tbody>
			<?php $count = 1; ?>
			<?php foreach ($messages as $message): ?>
				<?php $new = (!$message->is_read) ? true : false; ?>
				<tr>
					<td><?php echo $count; ?></td>
					<td><?php echo Html::l($message->id, $controller, $method, array($message->id));?></td>
					<td><?php echo $message->name;?></td>
					<td<?php echo ($new)? ' class="adm_contacts_is_new"' : ''; ?>><?php echo Html::l($message->subject, $controller, $method, array($message->id));?></td>
					<td><?php echo TimeHelper::getFormattedDate('d.m.Y \a\t H:i', $message->created);?></td>
					<td><?php echo ($new) ? 'new' : ''; ?></td>
				</tr>
				<?php $count++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>