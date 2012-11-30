<div class="messageBoxMenu messageBoxMenuActive">
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
<div class="messageBoxMenu">
	<?php echo Html::l($language->outbox, 'Message', 'outbox');?>
</div>
<div style="clear:both;"></div>

<div class="messageBox">
	<?php echo Form::start('form_reply_message'); ?>
		<table style="width:100%;">
			<thead>
				<tr>
					<th style="text-align:right; padding-right:10px;"><?php echo $language->to;?>:</th>
					<td>
						<?php echo Form::getError('to_username');?>
						<?php echo Form::liveSearch('to_username', $to_username = null, '/Message/ajax_live_search_username', 'query');?>

					</td>
				</tr>
				<tr>
					<th style="text-align:right; padding-right:10px;"><?php echo $language->subject; ?>:</th>
					<td>
						<?php echo Form::getError('subject');?>
						<?php echo Form::inputField('subject', null, array('size' => 55)); ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td>
						<?php echo Form::getError('message');?>
						<?php echo Form::textArea('message', 40, 6);	?>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td>
						<?php echo Form::submitButton('reply_submit', $language->send); ?>
					</td>
				</tr>
		</table>
	<?php echo Form::end(); ?>
</div>


