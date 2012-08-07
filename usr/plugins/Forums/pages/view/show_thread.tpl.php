<?php
$timestamp	= strtotime($thread['created']);
$thread_date= date($date_format, $timestamp);
$thread_time= date($time_format, $timestamp);
?>


<h1 class="forum"><?php echo $headline; ?></h1>

<table class="forum">
	<thead>
		<tr>
			<th colspan="2"><div class="forumNavi"><?php echo $navi; ?></div></th>
		</tr>
		<tr>
			<th colspan="2"><img src="/img/packages/forum/icon_date_time.png" title="<?php echo $language->date; ?>" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $thread_date.' '.$language->atTime.' '.$thread_time; ?></span></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td></td>
			<td>
				<div>
					<div style="float:left;">
						<?php echo (strtotime($thread['modified'])>0)? $language->editedOn.' '.date($date_format, strtotime($thread['modified'])).' '.$language->atTime.' '.date($time_format, strtotime($thread['modified'])).' ':''; ?>
					</div>
					<div class="borderMe" style="float:right;">
						<?php if ( $thread['fk_user_id'] == Users::id() ):?>
							<img src="/img/packages/forum/button_edit.png" alt="edit" onclick="quickEditThread(<?php echo $thread['id'];?>);" />
						<?php endif; ?>
					</div>
					<div style="float:right;">
						<?php if ( Users::id()>0 ):?>
							<?php echo Form::start('form_add_post', '/Forums/addPost/'.$forum_id.'/'.$thread_id, array('id' => 'form_post_quote_'.$forum_id.'_'.$thread_id)); ?>
								<?php echo Form::inputHidden('thisBody', htmlentities($thread['body']), array('id' => 'thisBodyId'.$thread['id'])); ?>
								<?php echo Form::inputHidden('body', htmlentities($thread['body']), array('id' => 'bodyId')); ?>
								<?php echo Form::inputHidden('forum_id', $forum_id); ?>
								<?php echo Form::inputHidden('thread_id', $thread_id); ?>
								<img src="/img/packages/forum/button_quote.png" alt="quote"
									onclick="var body=(document.getElementById('thisBodyId<?php echo $thread['id'];?>').value);
											body='[quote=<?php echo $thread['username'];?>]'+(body)+'[/quote]';
											document.getElementById('bodyId').value=body;
											document.getElementById('form_post_quote_<?php echo $forum_id.'_'.$thread_id; ?>').submit();" />
							<?php echo Form::end(); ?>
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td style="width:150px;">
				<div class="forumUsername"><?php echo ($thread['fk_user_id']>0) ? Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : 'anonymous'; ?></div>
				<br/>
				<div style="font-size:11px;">
					<div style="float:left; width:90px;"><?php echo $language->entries; ?>:</div><div style="float:left;"><?php echo $thread['num_entries'];?></div>
					<br/>
				</div><br/><br/>
				<div style="clear:both;"></div>
				<div>
					<div style="font-size:11px;">
						<a href="/<?php echo $userMessageToCtl.'/'.$userMessageToMethod.'/'.$thread['fk_user_id'];?>" title="send private PM to <?php echo $thread['username']; ?>">
							<img src="/img/packages/forum/pm.png" title="Private Nachricht" style="vertical-align:middle;"/> <?php echo $language->sendMessage;?>
						</a>
					</div>
				</div>
			</td>
			<td>
				<div id="startThread">
					<div class="forumThreadTitle"><?php echo $thread['title']; ?></div>
					<div class="forumThreadBody"><?php echo Bbcode::parse($thread['body']); ?></div>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<?php foreach ($posts as $post): ?>
	<?php
		$timestamp	= strtotime($post['created']);
		$date		= date($date_format, $timestamp);
		$time		= date($time_format, $timestamp);
	?>
	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><img src="/img/packages/forum/icon_date_time.png" title="<?php echo $language->date;?>" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $date.' '.$language->atTime.' '.$time; ?></span></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
				<td>
					<div>
						<div style="float:left;">
							<?php echo (strtotime($post['modified'])>0)? $language->editedOn.' '.date($date_format, strtotime($post['modified'])).' '.$language->atTime.' '.date($time_format, strtotime($post['modified'])).'':''; ?>
						</div>
						<div style="float:right;">
							<?php if ( $post['fk_user_id'] == Users::id() ):?>
								<img src="/img/packages/forum/button_edit.png" alt="edit" onclick="quickEditPost(<?php echo $post['id'];?>);" />
							<?php endif; ?>
						</div>
						<div style="float:right;">
							<?php if ( Users::id()>0 ):?>
								<?php echo Form::start('form_add_post', '/Forums/addPost/'.$forum_id.'/'.$thread_id, array('id' => 'form_post_quote_'.$forum_id.'_'.$thread_id)); ?>
									<?php echo Form::inputHidden('thisBody', $post['body'], array('id' => 'thisBodyId'.$post['id'])); ?>
									<?php echo Form::inputHidden('body', $post['body'], array('id' => 'bodyId')); ?>
									<?php echo Form::inputHidden('forum_id', $forum_id); ?>
									<?php echo Form::inputHidden('thread_id', $thread_id); ?>
									<img src="/img/packages/forum/button_quote.png" alt="quote"
										onclick="var body=document.getElementById('thisBodyId<?php echo $post['id'];?>').value;
												body='[quote=<?php echo $post['username'];?>]'+body+'[/quote]';
												document.getElementById('bodyId').value=body;
												document.getElementById('form_post_quote_<?php echo $forum_id.'_'.$thread_id; ?>').submit();" />
								<?php echo Form::end(); ?>
							<?php endif; ?>
						</div>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="width:150px;">
					<div class="forumUsername"><?php echo ($post['fk_user_id']>0) ? Html::l($post['username'], $userProfileCtl, $userProfileMethod, array($post['fk_user_id'])) : 'anonymous'; ?></div>
					<br/><br/>
					<div style="font-size:11px;">
						<div style="float:left; width:90px;"><?php echo $language->entries; ?>:</div><div style="float:left;"><?php echo $post['num_entries'];?></div>
						<br/>
					</div><br/><br/>
					<div style="clear:both;"></div>
					<div>
						<div style="font-size:11px;">
							<a href="/<?php echo $userMessageToCtl.'/'.$userMessageToMethod.'/'.$post['fk_user_id'];?>" title="private Nachricht an <?php echo $thread['username']; ?> schreiben">
								<img src="/img/packages/forum/pm.png" title="Private Nachricht" style="vertical-align:middle;"/> <?php echo $language->sendMessage;?>
							</a>
						</div>
					</div>
				</td>
				<td>
					<div id="post_<?php echo $post['id']; ?>">
						<?php if (strlen($post['title'])): ?>
							<div class="forumPostTitle"><?php echo $post['title']; ?></div>
						<?php endif; ?>
						<div class="forumPostBody"><?php echo Bbcode::parse($post['body']); ?></div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach; ?>


<?php if (!$can_reply): ?>
	<p><?php echo $language->cantReply; ?></p>
<?php elseif (!Users::isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $language->directAnswer; ?></div>
	<p><?php echo $language->replyLoginNote;?> <?php echo Html::l($language->here, $userLoginCtl,$userLoginMethod);?> <?php echo $language->or; ?> <?php echo Html::l($language->registerFree, $userRegisterCtl,$userRegisterMethod);?></p>
<?php elseif ($thread['is_closed']): ?>
	<p><?php echo $language->hasBeenClosed;?></p>
<?php elseif ($thread['is_locked']): ?>
	<p><?php echo $language->hasBeenLocked;?></p>
<?php else: ?>
	<br/>
	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><?php echo $language->directAnswer; ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="width:150px;">

				</td>
				<td>
					<?php
					echo Form::start('form_add_post', null, array('id' => 'formAddPostId'));
						echo Form::getError('forum_id');
						echo Form::getError('thread_id');
						echo Form::getError('body'); ?>
						<?php echo $language->message; ?>:
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<div style="height:20px;">
								<?php echo $messageBBCodeIconBar; ?>
							</div>
							<div>
								<?php echo Form::textArea('body', 60, 5, null, array('id' => 'postMessage'));	?>
							</div>
						</div><br/>
						<?php
						echo Form::inputHidden('forum_id', $forum_id);
						echo Form::inputHidden('thread_id', $thread_id);
						echo Form::submitButton('add_comment_submit', $language->answer);
						echo Form::submitButton('add_comment_advanced', $language->advanced, array('onClick' => 'document.getElementById(\'formAddPostId\').action=\'/Forums/addPost/'.$forum_id.'/'.$thread_id.'\''));
					echo Form::end();
					?><br/>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif;?>

<table class="forum">
	<tfoot>
		<tr>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<td>
				<?php echo $blocks['onlineUsers'];?>
			</td>
		</tr>
	</tfoot>
</table>