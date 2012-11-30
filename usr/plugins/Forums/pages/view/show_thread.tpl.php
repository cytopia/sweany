<?php
$thread_date= TimeHelper::date($date_format, $Thread->created);
$thread_time= TimeHelper::date($time_format, $Thread->created);
?>


<h1 class="forum"><?php echo $headline; ?></h1>

<table class="forum">
	<thead>
		<tr>
			<th colspan="2"><div class="forumNavi"><?php echo $navi; ?></div></th>
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
			<th colspan="2"><img src="/plugins/Forums/img/icon_date_time.png" title="<?php echo $language->date; ?>" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $thread_date.' '.$language->atTime.' '.$thread_time; ?></span></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td></td>
			<td>
				<div>
					<div style="float:left;">
						<?php echo ($Thread->modified>0)? $language->editedOn.' '.TimeHelper::date($date_format, $Thread->modified).' '.$language->atTime.' '.TimeHelper::date($time_format, $Thread->modified).' ':''; ?>
					</div>
					<div style="float:right;">
						<?php if ( $Thread->fk_user_id == $user->id() ):?>
							<img src="/plugins/Forums/img/button_edit.png" alt="edit" onclick="quickEditThread(<?php echo $Thread->id;?>);" />
						<?php endif; ?>
					</div>
					<div style="float:right;">
						<?php if ( $user->id()>0 ):?>
							<?php echo Form::start('form_add_post', '/Forums/addPost/'.$Thread->Forum->id.'/'.$Thread->id, array('id' => 'form_post_quote_'.$Thread->Forum->id.'_'.$Thread->id)); ?>
								<?php echo Form::inputHidden('thisBody', htmlentities($Thread->body), array('id' => 'thisBodyId'.$Thread->id)); ?>
								<?php echo Form::inputHidden('body', htmlentities($Thread->body), array('id' => 'bodyId')); ?>
								<?php echo Form::inputHidden('forum_id', $Thread->Forum->id); ?>
								<?php echo Form::inputHidden('thread_id', $Thread->id); ?>
								<img src="/plugins/Forums/img/button_quote.png" alt="quote"
									onclick="var body=(document.getElementById('thisBodyId<?php echo $Thread->id;?>').value);
											body='[quote=<?php echo $Thread->User->username;?>]'+(body)+'[/quote]';
											document.getElementById('bodyId').value=body;
											document.getElementById('form_post_quote_<?php echo $Thread->Forum->id.'_'.$Thread->id; ?>').submit();" />
							<?php echo Form::end(); ?>
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php
			$author_name= ($Thread->User->id>0) ? $Thread->User->username : 'anonymous';
			$author_link= ($userProfileLink) ? Html::l($author_name, $userProfileCtl, $userProfileMethod, array($Thread->User->id)) : $author_name;
		?>
		<tr>
			<td style="width:150px;">
				<div class="forumUsername"><?php echo $author_link; ?></div>
				<br/>
				<div style="font-size:11px;">
					<div style="float:left; width:90px;"><?php echo $language->entries; ?>:</div><div style="float:left;"><?php echo $Thread->User->num_entries;?></div>
					<br/>
				</div><br/><br/>
				<div style="clear:both;"></div>
				<div>
					<?php if ( $userMessageLink ):?>
						<div style="font-size:11px;">
							<a href="/<?php echo $userMessageToCtl.'/'.$userMessageToMethod.'/'.$Thread->User->id;?>" title="<?php echo $language->sendMessage;?>">
								<img src="/plugins/Forums/img/pm.png" title="<?php echo $language->sendMessage;?>" style="vertical-align:middle;"/> <?php echo $language->sendMessage;?>
							</a>
						</div>
					<?php endif;?>
				</div>
			</td>
			<td>
				<div id="startThread">
					<div class="forumThreadTitle"><?php echo $Thread->title; ?></div>
					<div class="forumThreadBody"><?php echo Bbcode::parse($Thread->body); ?></div>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<?php foreach ($Thread->Post as $Post): ?>
	<?php
		$timestamp	= $Post->created;
		$date		= TimeHelper::date($date_format, $timestamp);
		$time		= TimeHelper::date($time_format, $timestamp);
	?>
	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><img src="/plugins/Forums/img/icon_date_time.png" title="<?php echo $language->date;?>" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $date.' '.$language->atTime.' '.$time; ?></span></th>
			</tr>		<tr>
			<td colspan="6"></td>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
				<td>
					<div>
						<div style="float:left;">
							<?php echo ($Post->modified>0)? $language->editedOn.' '.TimeHelper::date($date_format, $Post->modified).' '.$language->atTime.' '.TimeHelper::date($time_format, $Post->modified).'':''; ?>
						</div>
						<div style="float:right;">
							<?php if ( $Post->fk_user_id == $user->id() ):?>
								<img src="/plugins/Forums/img/button_edit.png" alt="edit" onclick="quickEditPost(<?php echo $Post->id;?>);" />
							<?php endif; ?>
						</div>
						<div style="float:right;">
							<?php if ( $user->id()>0 ):?>
								<?php echo Form::start('form_add_post', '/Forums/addPost/'.$Thread->Forum->id.'/'.$Thread->id, array('id' => 'form_post_quote_'.$Thread->Forum->id.'_'.$Thread->id)); ?>
									<?php echo Form::inputHidden('thisBody', $Post->body, array('id' => 'thisBodyId'.$Post->id)); ?>
									<?php echo Form::inputHidden('body', $Post->body, array('id' => 'bodyId')); ?>
									<?php echo Form::inputHidden('forum_id', $Thread->Forum->id); ?>
									<?php echo Form::inputHidden('thread_id', $Thread->id); ?>
									<img src="/plugins/Forums/img/button_quote.png" alt="quote"
										onclick="var body=document.getElementById('thisBodyId<?php echo $Post->id;?>').value;
												body='[quote=<?php echo $Post->username;?>]'+body+'[/quote]';
												document.getElementById('bodyId').value=body;
												document.getElementById('form_post_quote_<?php echo $Thread->Forum->id.'_'.$Thread->id; ?>').submit();" />
								<?php echo Form::end(); ?>
							<?php endif; ?>
						</div>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php
				$author_name= ($Post->fk_user_id>0) ? $Post->username : 'anonymous';
				$author_link= ($userProfileLink) ? Html::l($author_name, $userProfileCtl, $userProfileMethod, array($Post->fk_user_id)) : $author_name;
			?>
			<tr>
				<td style="width:150px;">
					<div class="forumUsername"><?php echo $author_link; ?></div>
					<br/><br/>
					<div style="font-size:11px;">
						<div style="float:left; width:90px;"><?php echo $language->entries; ?>:</div><div style="float:left;"><?php echo $Post->num_entries;?></div>
						<br/>
					</div><br/><br/>
					<div style="clear:both;"></div>
					<div>
						<?php if ( $userMessageLink ):?>
							<div style="font-size:11px;">
								<a href="/<?php echo $userMessageToCtl.'/'.$userMessageToMethod.'/'.$Post->fk_user_id;?>" title="private Nachricht an <?php echo $Thread->User->username; ?> schreiben">
									<img src="/plugins/Forums/img/pm.png" title="Private Nachricht" style="vertical-align:middle;"/> <?php echo $language->sendMessage;?>
								</a>
							</div>
						<?php endif;?>
					</div>
				</td>
				<td>
					<div id="post_<?php echo $Post->id; ?>">
						<?php if (strlen($Post->title)): ?>
							<div class="forumPostTitle"><?php echo $Post->title; ?></div>
						<?php endif; ?>
						<div class="forumPostBody"><?php echo Bbcode::parse($Post->body); ?></div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach; ?>


<?php if (!$Thread->Forum->can_reply): ?>
	<p><?php echo $language->cantReply; ?></p>
<?php elseif (!$user->isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $language->directAnswer; ?></div>
	<p><?php echo $language->replyLoginNote;?> <?php echo Html::l($language->here, $userLoginCtl,$userLoginMethod);?> <?php echo $language->or; ?> <?php echo Html::l($language->registerFree, $userRegisterCtl,$userRegisterMethod);?></p>
<?php elseif ($Thread->is_closed): ?>
	<p><?php echo $language->hasBeenClosed;?></p>
<?php elseif ($Thread->is_locked): ?>
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
					echo Form::start('form_add_post', '/Forums/addPost/'.$Thread->Forum->id.'/'.$Thread->id, array('id' => 'formAddPostId'));
						echo Form::getError('forum_id');
						echo Form::getError('thread_id');
						echo Form::getError('body'); ?>
						<?php echo $language->message; ?>:
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<?php echo Form::editor('body', null, 60, 5, array('id' => 'postMessage'))?>
						</div><br/>
						<?php
						echo Form::inputHidden('forum_id', $Thread->Forum->id);
						echo Form::inputHidden('thread_id', $Thread->id);
						echo Form::submitButton('add_post_submit', $language->answer);
						echo Form::submitButton('add_comment_advanced', $language->advanced, array('onClick' => 'document.getElementById(\'formAddPostId\').action=\'/Forums/addPost/'.$Thread->Forum->id.'/'.$Thread->id.'\''));
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
				<?php echo $bOnlineUsers;?>
			</td>
		</tr>
	</tfoot>
</table>