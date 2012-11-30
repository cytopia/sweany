<div class="messageBoxMenu">
	<?php echo Html::l($language->new, 'Message', 'write');?>
</div>
<div class="messageBoxMenu messageBoxMenuActive">
	<?php echo Html::l($language->inbox, 'Message', 'inbox');?>
</div>
<div class="messageBoxMenu">
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
	<?php echo Form::start('form_message_action', null, array('id' => 'form_message_action')); ?>
		<table>
			<colgroup>
				<col width="20" />
				<col width="80" />
				<col width="260" />
				<col width="100" />
				<col />
				<col width="20" />
			</colgroup>
			<thead>
				<tr>
					<td colspan="6">
						<div id="trash" class="msgActionButtons" style="float:right;"
							onclick="document.getElementById('trash').innerHTML += '<input type=&quot;hidden&quot; name=&quot;form_message_action[trash]&quot; />';
								document.getElementById('form_message_action').submit();">
							<?php echo Html::img('/plugins/Message/img/action_trash.png', $language->moveToTrash); ?>
							<br/>
							<span><?php echo $language->trash;?></span>
						</div>
						<div id="archive" class="msgActionButtons" style="float:right;"
							onclick="document.getElementById('archive').innerHTML += '<input type=&quot;hidden&quot; name=&quot;form_message_action[archive]&quot; />';
								document.getElementById('form_message_action').submit();">
							<?php echo Html::img('/plugins/Message/img/action_archive.png', $language->moveToArchive); ?>
							<br/>
							<span><?php echo $language->moveToArchive; ?></span>
						</div>
						<div style="clear:both;"></div>
						<hr />
					</td>
				</tr>
				<tr>
					<th style="text-align:center;">#</th>
					<th><?php echo $language->from;?></th>
					<th><?php echo $language->subject;?></th>
					<th colspan="2"><?php echo $language->received;?></th>
					<th style="text-align:center;"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan=6><hr/></th>
				</tr>
				<tr>
					<td colspan="6">
						<?php echo Html::img('/plugins/Message/img/flag_msg_new.png', $language->newMessage); ?> <?php echo $language->newMessage;?><br/>
						<?php echo Html::img('/plugins/Message/img/flag_msg_read.png', $language->readMessage); ?> <?php echo $language->readMessage;?><br/>
						<?php echo Html::img('/plugins/Message/img/flag_msg_replied.png', $language->repliedMessage); ?> <?php echo $language->repliedMessage;?><br/>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php $i=0; ?>
				<?php foreach ($messages as $message): ?>
					<?php $style 		= ( !$message->is_read )? array('style' => 'font-weight: bold;') : null; ?>
					<?php $flag			= ( $message->is_read )	? ( ($message->is_answered)? 'flag_msg_replied.png': 'flag_msg_read.png') : 'flag_msg_new.png'; ?>
					<?php $flag_img		= Html::img('/plugins/Message/img/'.$flag, 'flag'); ?>
					<?php $user_link	= ($message->fk_from_user_id) ? Html::l($message->from_username, $userProfileCtl, $userProfileMethod, array($message->fk_from_user_id)) : $systemUserName; ?>
					<tr>
						<td><?php echo $flag_img; ?></td>
						<td><?php echo $user_link; ?></td>
						<td><?php echo Html::l($message->subject, $className, 'read', array($message->id, 'inbox'), $style); ?></td>
						<td><?php echo TimeHelper::weekDayShort($message->created).', '.TimeHelper::date($language->getCustom('/root/core/settings', 'date_format'), $message->created); ?></td>
						<td><?php echo TimeHelper::date('H:i', $message->created); ?></td>
						<td style="text-align:center;"><?php echo Form::checkBox('message_id['.($i).']', $message->id); ?></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php echo Form::end(); ?>
</div>