<table style="width:100%;">	<thead>		<tr>			<th colspan="2">				<?php if ( ($type == 'inbox') ): ?>					<div class="msgActionButtons" onclick="window.location.href='/Nachrichten/reply/<?php echo $message['fk_from_user_id']; ?>/<?php echo $message['id']; ?>';">						<img src="/img/packages/user_messages/reply.png" /><br/>						<span>Antworten</span>					</div>				<?php endif; ?>				<?php echo $form->start('form_message_action'); ?>					<?php /* TRASH or OUTBOX MESSAGE: can delete */ ?>					<?php if ( ($type == 'outbox') || ($type == 'inbox') && ($message['is_received_trashed']==1) ):?>						<div class="msgActionButtons" style="float:right;">							<?php echo $form->imgSubmitButton('delete', 'Delete', '/img/packages/user_messages/delete.png'); ?>							<br/>							<span>L&ouml;schen</span>						</div>					<?php endif;?>					<?php /* INBOX or ARCHIVE MESSAGE: can trash */ ?>					<?php if ( ($type == 'inbox') && $message['is_received_trashed']==0 ):?>						<div class="msgActionButtons" style="float:right;">							<?php echo $form->imgSubmitButton('trash', 'Trash', '/img/packages/user_messages/trash.png'); ?>							<br/>							<span>Verschieben</span>						</div>					<?php endif;?>					<?php /* TRASH MESSAGE: can restore */ ?>					<?php if ( ($type == 'inbox') && ($message['is_received_trashed']==1) ):?>						<div class="msgActionButtons" style="float:right;">							<?php echo $form->imgSubmitButton('restore', 'Restore', '/img/packages/user_messages/restore.png'); ?>							<br/>							<span>Restoren</span>						</div>					<?php endif;?>					<?php /* INBOX MESSAGE: can archive */ ?>					<?php if ( ($type == 'inbox') && ($message['is_received_archived']==0) && ($message['is_received_trashed']==0) ):?>						<div class="msgActionButtons" style="float:right;">							<?php echo $form->imgSubmitButton('archive', 'Archive', '/img/packages/user_messages/archive.png'); ?>							<br/>							<span>Archivieren</span>						</div>					<?php endif;?>					<div style="clear:both;"></div>				<?php echo $form->end(); ?>				<hr/>			</th>		</tr>	</thead>	<tfoot>		<tr>			<th colspan="2"></th>		</tr>	</tfoot>	<tbody>		<tr>			<th style="text-align:right; padding-right:10px; width:60px;"><?php echo ($type=='inbox')?'Empfangen:':'Gesendet:'; ?></th>			<td><?php echo MyTime::getWeekDay(strtotime($message['created'])).', '.date('d.m.Y', strtotime($message['created'])).', '.date('H:i', strtotime($message['created'])); ?> Uhr</td>		</tr>		<tr>			<th style="text-align:right; padding-right:10px;">Von:</th>			<td><?php echo ($message['fk_from_user_id']) ? Html::l($message['from_username'], $userProfileCtl, $userProfileMethod, array($message['fk_from_user_id'])) : $systemUserName; ?></td>		</tr>		<tr>			<th style="text-align:right; padding-right:10px;">An:</th>			<td><?php echo Html::l($message['to_username'], $userProfileCtl, $userProfileMethod, array($message['fk_to_user_id'])); ?></td>		</tr>		<tr>			<th style="text-align:right; padding-right:10px;">Betreff:</th>			<td><?php echo $message['subject']; ?></td>		</tr>	</tbody></table><div class="msgBodyDisplayBox">	<?php echo Bbcode::parse($message['message']); ?></div>