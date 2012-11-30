<div class="messageBoxMenu">
	<?php echo Html::l($language->new, 'Message', 'write');?>
</div>
<div class="messageBoxMenu<?php echo ($type == 'inbox')? ' messageBoxMenuActive' : ''; ?>">
	<?php echo Html::l($language->inbox, 'Message', 'inbox');?>
</div>
<div class="messageBoxMenu">
	<?php echo Html::l($language->alerts, 'Message', 'alerts');?>
</div>
<div class="messageBoxMenu<?php echo ($type == 'archive')? ' messageBoxMenuActive' : ''; ?>">
	<?php echo Html::l($language->archive, 'Message', 'archive');?>
</div>
<div class="messageBoxMenu<?php echo ($type == 'trash')? ' messageBoxMenuActive' : ''; ?>">
	<?php echo Html::l($language->trash, 'Message', 'trash');?>
</div>
<div class="messageBoxMenu<?php echo ($type == 'outbox')? ' messageBoxMenuActive' : ''; ?>">
	<?php echo Html::l($language->outbox, 'Message', 'outbox');?>
</div>
<div style="clear:both;"></div>
<div class="messageBox">
	<table style="width:100%;">
		<thead>
			<tr>
				<th colspan="2">
					<?php if ( ($type == 'inbox') ): ?>
						<div class="msgActionButtons" onclick="window.location.href='<?php echo Html::href('Message', 'reply', array($message->fk_from_user_id, $message->id)); ?>';">
							<img src="/plugins/Message/img/action_reply.png" /><br/>
							<span><?php echo $language->reply?></span>
						</div>
					<?php endif; ?>
					<?php echo Form::start('form_message_action'); ?>

						<?php /* TRASH or OUTBOX MESSAGE: can delete */ ?>
						<?php if ( ($type == 'trash') || ($type == 'outbox') ):?>
							<div class="msgActionButtons" style="float:right;">
								<?php echo Form::imgSubmitButton('delete', $language->delete, '/plugins/Message/img/action_delete.png'); ?>
								<br/>
								<span><?php echo $language->delete;?></span>
							</div>
						<?php endif;?>

						<?php /* INBOX or ARCHIVE MESSAGE: can trash */ ?>
						<?php if ( ($type == 'inbox') || ($type == 'archive') ):?>
							<div class="msgActionButtons" style="float:right;">
								<?php echo Form::imgSubmitButton('trash', $language->trash, '/plugins/Message/img/action_trash.png'); ?>
								<br/>
								<span><?php echo $language->moveToTrash;?></span>
							</div>
						<?php endif;?>

						<?php /* TRASH MESSAGE: can restore */ ?>
						<?php if ( ($type == 'trash') ):?>
							<div class="msgActionButtons" style="float:right;">
								<?php echo Form::imgSubmitButton('restore', $language->restore, '/plugins/Message/img/action_restore.png'); ?>
								<br/>
								<span><?php echo $language->restore;?></span>
							</div>
						<?php endif;?>

						<?php /* INBOX MESSAGE: can archive */ ?>
						<?php if ( ($type == 'inbox') ):?>
							<div class="msgActionButtons" style="float:right;">
								<?php echo Form::imgSubmitButton('archive', $language->archive, '/plugins/Message/img/action_archive.png'); ?>
								<br/>
								<span><?php echo $language->archive;?></span>
							</div>
						<?php endif;?>

						<div style="clear:both;"></div>
					<?php echo Form::end(); ?>
					<hr/>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="2"></th>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<th style="text-align:right; padding-right:10px; width:60px;"><?php echo ($type=='inbox')?$language->received.':' : $language->sent.':'; ?></th>
				<td><?php echo TimeHelper::weekDay($message->created).', '.TimeHelper::date($language->getCustom('/root/core/settings', 'date_format'), $message->created).', '.TimeHelper::date('H:i', $message->created); ?></td>
			</tr>
			<tr>
				<th style="text-align:right; padding-right:10px;"><?php echo $language->from;?>:</th>
				<td><?php echo ($message->fk_from_user_id) ? Html::l($message->from_username, $userProfileCtl, $userProfileMethod, array($message->fk_from_user_id)) : $systemUserName; ?></td>
			</tr>
			<tr>
				<th style="text-align:right; padding-right:10px;"><?php echo $language->to;?>:</th>
				<td><?php echo Html::l($message->to_username, $userProfileCtl, $userProfileMethod, array($message->fk_to_user_id)); ?></td>
			</tr>
			<tr>
				<th style="text-align:right; padding-right:10px;"><?php echo $language->subject;?>:</th>
				<td><?php echo $message->subject; ?></td>
			</tr>
		</tbody>
	</table>
	<div class="msgBodyDisplayBox">
		<?php echo Bbcode::parse($message->message); ?>
	</div>
</div>