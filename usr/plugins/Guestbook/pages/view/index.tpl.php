<a href="#addEntry"><?php echo $language->add_entry; ?></a>
<br/>
<hr/>
<br/>

<table>
<?php foreach($entries as $entry): ?>
	<tr>
		<td>
			<div id="<?php echo $entry->id;?>" style="text-align:center;width:128px;">
				<strong>
					<?php echo $entry->fk_user_id ? Html::l($entry->username, 'User', 'show', array($entry->fk_user_id)) : $entry->author; ?><br/>
				</strong>
				<?php echo strlen($entry->avatar) ? Html::img('/plugins/Guestbook/img/avatars/'.$entry->avatar, 'avatar', array('width' => '64', 'height' => '64')) : '';?>
			</div>
		</td>
		<td style="vertical-align:middle">
			<?php echo Bbcode::parse($entry->message); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<?php echo TimeHelper::date('d.m.Y', $entry->created).' '.TimeHelper::date('H:i', $entry->created); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr/></td>
	</tr>
<?php endforeach; ?>
</table>
<br/>
<hr/>
<br/>
<div id="addEntry">
	<?php echo $bAddEntry; ?>
</div>
