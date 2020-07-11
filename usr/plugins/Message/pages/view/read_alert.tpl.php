<div class="messageBoxMenu">
	<?php echo Html::l($language->new, 'Message', 'write');?>
</div>
<div class="messageBoxMenu">
	<?php echo Html::l($language->inbox, 'Message', 'inbox');?>
</div>
<div class="messageBoxMenu messageBoxMenuActive">
	<?php echo Html::l($language->alerts, 'Message', 'alerts');?>
</div>
<div class="messageBoxMenu">
	<?php echo Html::l($language->archive, 'Message', 'archive');?>
</div>
<div class="messageBoxMenu">
	<?php echo Html::l($language->trash, 'Message', 'trash');?>
</div>
<div class="messageBoxMenu">
	<?php echo Html::l($language->outbox, 'Message', 'outbox');?>
</div><div style="clear:both;"></div>
<div class="messageBox">
	<table style="width:100%;">
		<tfoot>
			<tr>
				<th colspan="2"></th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<th style="text-align:right; padding-right:10px; width:60px;"><?php echo $language->received; ?></th>
				<td><?php echo TimeHelper::weekDay($message->created).', '.TimeHelper::date($language->getCustom('/root/core/settings', 'date_format'), $message->created).', '.TimeHelper::date('H:i', $message->created); ?></td>
			</tr>
			<tr>
				<th style="text-align:right; padding-right:10px;"><?php echo $language->from;?>:</th>
				<td><?php echo $systemUserName;?></td>
			</tr>
			<tr>
				<th style="text-align:right; padding-right:10px;"><?php echo $language->subject?>:</th>
				<td><?php echo $message->subject; ?></td>
			</tr>
		</tbody>
	</table>
	<div class="msgBodyDisplayBox">
		<?php echo Bbcode::parse($message->message); ?>
	</div>
</div>
