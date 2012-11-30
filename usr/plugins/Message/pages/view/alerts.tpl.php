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
</div>
<div style="clear:both;"></div>
<div class="messageBox">
	<table>
		<colgroup>
			<col width="20" />
			<col width="80" />
			<col width="260" />
			<col width="100" />
			<col />
		</colgroup>
		<thead>
			<tr>
				<th style="text-align:center;">#</th>
				<th><?php echo $language->from;?></th>
				<th><?php echo $language->subject;?></th>
				<th colspan="2"><?php echo $language->received;?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan=5><hr/></th>
			</tr>
			<tr>
				<td colspan="5">
					<?php echo Html::img('/plugins/Message/img/flag_msg_new.png', 'unread'); ?> <?php echo $language->newAlert;?><br/>
					<?php echo Html::img('/plugins/Message/img/flag_msg_read.png', 'is read'); ?> <?php echo $language->readAlert;?><br/>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php $i=0; ?>
			<?php foreach ($messages as $message): ?>
				<?php $style 		= ( !$message->is_read )? array('style' => 'font-weight: bold;') : null; ?>
				<?php $flag			= ( $message->is_read )	? 'flag_msg_read.png' : 'flag_msg_new.png'; ?>
				<?php $flag_img		= Html::img('/plugins/Message/img/'.$flag, 'flag'); ?>
				<tr>
					<td><?php echo $flag_img; ?></td>
					<td><?php echo $systemUserName;?></td>
					<td><?php echo Html::l($message->subject, $className, 'alert', array($message->id), $style); ?></td>
					<td><?php echo TimeHelper::weekDayShort($message->created).', '.TimeHelper::date($language->getCustom('/root/core/settings', 'date_format'), $message->created); ?></td>
					<td><?php echo TimeHelper::date('H:i', $message->created); ?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>