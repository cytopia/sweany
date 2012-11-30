<table>
<?php foreach($entries as $entry): ?>
	<?php $user = $entry->fk_user_id ? Html::l($entry->username, 'User', 'show', array($entry->fk_user_id)) : $entry->author; ?>
	<tr>
		<td style="vertical-align:middle">
			<?php echo trim(Strings::shorten(Bbcode::parse($entry->message), 150, true)); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo $user.' | '.TimeHelper::date('d.m.Y', $entry->created).' '.TimeHelper::date('H:i', $entry->created); ?>
		</td>
	</tr>
	<tr>
		<td><hr/></td>
	</tr>
<?php endforeach; ?>
</table>