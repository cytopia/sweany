<?php if ($can_message): ?>	<?php echo $form->start('form_reply_message'); ?>		<table style="width:100%;">			<thead>				<tr>					<th style="text-align:right; padding-right:10px;">An:</th>					<td><?php echo Html::l($to_username, $GLOBALS['DEFAULT_PROFILE_SHOW_CTL'], $GLOBALS['DEFAULT_PROFILE_SHOW_METHOD'], array($to_user_id)); ?></td>				</tr>				<tr>					<th style="text-align:right; padding-right:10px;">Betreff:</th>					<td>						<?php echo $form->getError('subject');?>						<?php echo $form->inputField('subject', null, array('size' => 55)); ?>					</td>				</tr>			</thead>			<tbody>				<tr>					<td></td>					<td>						<?php echo $form->getError('message');?>						<?php echo $form->textArea('message', 40, 6);	?>					</td>				</tr>			</tbody>			<tfoot>				<tr>					<td></td>					<td>						<?php echo $form->inputHidden('to_user_id', $to_user_id); ?>						<?php echo $form->submitButton('reply_submit', 'antworten'); ?>					</td>				</tr>		</table>	<?php echo $form->end(); ?><?php else: ?>	<p>Ung&uuml;tiger Benutzer.</p><?php endif; ?>