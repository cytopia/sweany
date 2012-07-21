<div id="content_one_col">
<h1 class="forum"><?php echo $headline; ?></h1>


<?php if (!Users::isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $txtCreateNewThread; ?></div>
	<p>Um einen Beitrag zu erstellen musst du dich erst <?php echo $html->l('hier einloggen', $GLOBALS['DEFAULT_USER_LOGIN_CTL'],$GLOBALS['DEFAULT_USER_LOGIN_METHOD']);?> oder <?php echo $html->l('kostenlos registrieren', $GLOBALS['DEFAULT_USER_REGISTER_CTL'],$GLOBALS['DEFAULT_USER_REGISTER_METHOD']);?></p>
<?php else: ?>

	<?php if (isset($preview)): ?>
		<table class="forum">
			<thead>
				<tr>
					<th>Vorschau</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div>
							<strong><?php echo $threadPreview['title']; ?></strong><br/><hr/><br/>
							<?php echo Bbcode::parse($threadPreview['body']); ?>
							<br/><br/>
						</div>
					</td>
				</tr>
			</tbody>
		</table><br/>
	<?php endif; ?>


	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><?php echo $navi; ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="width:150px;"></td>
				<td>

					<div style="font-weight:bold; font-size:14px;"><?php echo $txtCreateNewThread; ?></div><br/>
					<?php
					echo $form->start('form_add_thread');
						echo $form->getError('forum_id');
						echo $txtTitle.':<br/>';
						echo $form->getError('title');
						echo $form->inputField('title', NULL, array('size' => 50)).BR;
						echo $txtMessage.':';
						echo $form->getError('body');	?>
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<div style="height:20px;">
								<?php echo $messageBBCodeIconBar; ?>
							</div>
							<div>
								<?php echo $form->textArea('body', 60, 14, null, array('id' => 'postBody'));?>
							</div>
						</div><br/>
						<?php
						echo $form->inputHidden('forum_id', $forum_id);
						echo $form->submitButton('add_thread_submit', $txtCreateThreadBtn);
						echo $form->submitButton('add_thread_preview', $txtPreviewBtn);
					echo $form->end();
					?><br/>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif;?>
<br/>
</div>

