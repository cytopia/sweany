<div class="messageBoxMenu">
	<?php echo Html::l($language->new, 'Message', 'write');?>
</div>
<div class="messageBoxMenu">
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
<div class="messageBoxMenu messageBoxMenuActive">
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
						<div id="delete" class="msgActionButtons" style="float:right;"
							onclick="document.getElementById('delete').innerHTML += '<input type=&quot;hidden&quot; name=&quot;form_message_action[delete]&quot; />';
								document.getElementById('form_message_action').submit();">
							<?php echo Html::img('/plugins/Message/img/action_delete.png', $language->delete); ?>
							<br/>
							<span><?php echo $language->delete;?></span>
						</div>
						<div style="clear:both;"></div>
						<hr />
					</td>
				</tr>
				<tr>
					<th style="text-align:center;">#</th>
					<th><?php echo $language->to?></th>
					<th><?php echo $language->subject;?></th>
					<th colspan="2"><?php echo $language->sent;?></th>
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
				<?php $i=1; ?>
				<?php foreach ($messages as $message): ?>
					<?php $user_link	= ($message->fk_to_user_id) ? Html::l($message->to_username, $userProfileCtl, $userProfileMethod, array($message->fk_to_user_id)) : $systemUserName; ?>
					<?php $flag			= ($message->is_read) ? ( ($message->is_answered)? 'flag_msg_replied.png': 'flag_msg_read.png') : 'flag_msg_new.png'; ?>
					<?php $flag_img		= Html::img('/plugins/Message/img/'.$flag, 'flag'); ?>
					<tr>
						<td><?php echo $flag_img; ?></td>
						<td><?php echo $user_link; ?></td>
						<td><?php echo Html::l($message->subject, $className, 'read', array($message->id, 'outbox')); ?></td>
						<td><?php echo TimeHelper::weekDayShort($message->created).', '.TimeHelper::date($language->getCustom('/root/core/settings', 'date_format'), $message->created); ?></td>
						<td><?php echo TimeHelper::date('H:i', $message->created); ?></td>
						<td style="text-align:center;"><?php echo Form::checkBox('message_id['.($i-1).']', $message->id); ?></td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php echo Form::end(); ?>
</div>
