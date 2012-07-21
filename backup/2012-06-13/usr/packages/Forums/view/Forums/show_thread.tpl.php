﻿<?php
$timestamp	= strtotime($thread['created']);
$thread_date= date($date_format, $timestamp);
$thread_time= date($time_format, $timestamp);
?>


<div id="content_one_col">
<h1 class="forum"><?php echo $headline; ?></h1>

<table class="forum">
	<thead>
		<tr>
			<th colspan="2"><?php echo $navi; ?></th>
		</tr>
		<tr>
			<th colspan="2"><img src="/img/packages/forum/icon_date_time.png" title="Uhrzeit" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $thread_date.' um '.$thread_time; ?> Uhr</span></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td></td>
			<td>
				<div>
					<div style="float:left;">
						<?php echo (strtotime($thread['modified'])>0)? 'ge&auml;ndert am '.date($date_format, strtotime($thread['modified'])).' um '.date($time_format, strtotime($thread['modified'])).' Uhr':''; ?>
					</div>
					<div style="float:right;">
						<?php if ( $thread['fk_user_id'] == Users::id() ):?>
							<img src="/img/packages/forum/button_edit.png" alt="edit" onclick="quickEditThread(<?php echo $thread['id'];?>);" />
						<?php endif; ?>
					</div>
					<div style="float:right;">
						<?php if ( Users::id()>0 ):?>
							<?php echo $form->start('form_add_post', '/Forums/addPost/'.$forum_id.'/'.$thread_id, array('id' => 'form_post_quote_'.$forum_id.'_'.$thread_id)); ?>
								<?php echo $form->inputHidden('thisBody', htmlentities($thread['body']), array('id' => 'thisBodyId'.$thread['id'])); ?>
								<?php echo $form->inputHidden('body', htmlentities($thread['body']), array('id' => 'bodyId')); ?>
								<?php echo $form->inputHidden('forum_id', $forum_id); ?>
								<?php echo $form->inputHidden('thread_id', $thread_id); ?>
								<img src="/img/packages/forum/button_quote.png" alt="quote"
									onclick="var body=(document.getElementById('thisBodyId<?php echo $thread['id'];?>').value);
											body='[quote=<?php echo $thread['username'];?>]'+(body)+'[/quote]';
											document.getElementById('bodyId').value=body;
											document.getElementById('form_post_quote_<?php echo $forum_id.'_'.$thread_id; ?>').submit();" />
							<?php echo $form->end(); ?>
						<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td style="width:150px;">
				<span class="threadUsername"><?php echo ($thread['fk_user_id']>0) ? $html->l($thread['username'], $GLOBALS['DEFAULT_PROFILE_SHOW_CTL'], $GLOBALS['DEFAULT_PROFILE_SHOW_METHOD'], array($thread['fk_user_id'])) : 'anonymous'; ?></span>
				<br/><br/>
				<div style="font-size:11px;">
					<div style="float:left; width:90px;">Beitr&auml;ge:</div><div style="float:left;"><?php echo $thread['num_entries'];?></div>
					<div style="float:left; width:90px;">Danksagungen:</div><div style="float:left;"><?php echo $thread['num_thanks'];?></div>
					<br/>
				</div><br/><br/>
				<div style="clear:both;"></div>
				<div>
					<div style="font-size:11px;">
						<a href="/<?php echo $GLOBALS['DEFAULT_MESSAGE_WRITE_CTL'].'/'.$GLOBALS['DEFAULT_MESSAGE_WRITE_METHOD'].'/'.$thread['fk_user_id'];?>" title="private Nachricht an <?php echo $thread['username']; ?> schreiben">
							<img src="/img/packages/forum/pm.png" title="Private Nachricht" style="vertical-align:middle;"/> PM senden
						</a>
					</div>
					<div id="appendThanksMessage" style="font-size:11px;">
						<a href="#" title="Bedanke dich" onclick="
							document.getElementById('appendThanksMessage').innerHTML+='<?php echo addslashes(htmlentities('<div id="thankError" style="color:red;"></div>')); ?>';
							thankUser(<?php echo $thread['fk_user_id']; ?>)">
								<?php echo Html::img('/img/site/16x16/thanks.png');?> Bedanken
						</a>
					</div>
				</div>
			</td>
			<td>
				<div id="startThread">
					<strong><?php echo $thread['title']; ?></strong><br/><hr/><br/>
					<?php echo Bbcode::parse($thread['body']); ?>
					<br/><br/>
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
				<th colspan="2"><img src="/img/packages/forum/icon_date_time.png" title="Uhrzeit" /><span style="padding-left:5px; font-weight:normal;"> <?php echo $date.' um '.$time; ?> Uhr</span></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td></td>
				<td>
					<div>
						<div style="float:left;">
							<?php echo (strtotime($post['modified'])>0)? 'ge&auml;ndert am '.date($date_format, strtotime($post['modified'])).' um '.date($time_format, strtotime($post['modified'])).' Uhr':''; ?>
						</div>
						<div style="float:right;">
							<?php if ( $post['fk_user_id'] == Users::id() ):?>
								<img src="/img/packages/forum/button_edit.png" alt="edit" onclick="quickEditPost(<?php echo $post['id'];?>);" />
							<?php endif; ?>
						</div>
						<div style="float:right;">
							<?php if ( Users::id()>0 ):?>
								<?php echo $form->start('form_add_post', '/Forums/addPost/'.$forum_id.'/'.$thread_id, array('id' => 'form_post_quote_'.$forum_id.'_'.$thread_id)); ?>
									<?php echo $form->inputHidden('thisBody', $post['body'], array('id' => 'thisBodyId'.$post['id'])); ?>
									<?php echo $form->inputHidden('body', $post['body'], array('id' => 'bodyId')); ?>
									<?php echo $form->inputHidden('forum_id', $forum_id); ?>
									<?php echo $form->inputHidden('thread_id', $thread_id); ?>
									<img src="/img/packages/forum/button_quote.png" alt="quote"
										onclick="var body=document.getElementById('thisBodyId<?php echo $post['id'];?>').value;
												body='[quote=<?php echo $post['username'];?>]'+body+'[/quote]';
												document.getElementById('bodyId').value=body;
												document.getElementById('form_post_quote_<?php echo $forum_id.'_'.$thread_id; ?>').submit();" />
								<?php echo $form->end(); ?>
							<?php endif; ?>
						</div>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="width:150px;">
					<span class="threadUsername"><?php echo ($post['fk_user_id']>0) ? $html->l($post['username'], $GLOBALS['DEFAULT_PROFILE_SHOW_CTL'], $GLOBALS['DEFAULT_PROFILE_SHOW_METHOD'], array($post['fk_user_id'])) : 'anonymous'; ?></span>
					<br/><br/>
					<div style="font-size:11px;">
						<div style="float:left; width:90px;">Beitr&auml;ge:</div><div style="float:left;"><?php echo $post['num_entries'];?></div>
						<div style="float:left; width:90px;">Danksagungen:</div><div style="float:left;"><?php echo $post['num_thanks'];?></div>
						<br/>
					</div><br/><br/>
					<div style="clear:both;"></div>
					<div>
						<div style="font-size:11px;">
							<a href="/<?php echo $GLOBALS['DEFAULT_MESSAGE_WRITE_CTL'].'/'.$GLOBALS['DEFAULT_MESSAGE_WRITE_METHOD'].'/'.$post['fk_user_id'];?>" title="private Nachricht an <?php echo $thread['username']; ?> schreiben">
								<img src="/img/packages/forum/pm.png" title="Private Nachricht" style="vertical-align:middle;"/> PM senden
							</a>
						</div>
					</div>
					<div id="appendThanksMessage<?php echo $post['id'];?>" style="font-size:11px;">
						<a href="#" title="Bedanke dich" onclick="
							document.getElementById('appendThanksMessage<?php echo $post['id'];?>').innerHTML+='<?php echo addslashes(htmlentities('<div id="thankError" style="color:red;"></div>')); ?>';
							thankUser(<?php echo $post['fk_user_id']; ?>)">
								<?php echo Html::img('/img/site/16x16/thanks.png');?> Bedanken
						</a>
					</div>
				</td>
				<td>
					<div id="post_<?php echo $post['id']; ?>">
						<strong><?php echo $post['title']; ?></strong><br/><hr/><br/>
						<?php echo Bbcode::parse($post['body']); ?>
						<br/><br/>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach; ?>

<?php if (!$can_reply): ?>
	<p><?php echo $txtCannotReply; ?></p>
<?php elseif (!Users::isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $txtDirectAnswer; ?></div>
	<p>Um eine Antwort zu schreiben musst du dich erst <?php echo $html->l('hier einloggen', $GLOBALS['DEFAULT_USER_LOGIN_CTL'],$GLOBALS['DEFAULT_USER_LOGIN_METHOD']);?> oder <?php echo $html->l('kostenlos registrieren', $GLOBALS['DEFAULT_USER_REGISTER_CTL'],$GLOBALS['DEFAULT_USER_REGISTER_METHOD']);?></p>
<?php elseif ($thread['is_closed']): ?>
	<p>Der Beitrag wurde geschlossen.</p>
<?php elseif ($thread['is_locked']): ?>
		<p>Der Beitrag wurde gesperrt.</p>
<?php else: ?>
	<br/>
	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><?php echo $txtDirectAnswer; ?></th>
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
					echo $form->start('form_add_post', null, array('id' => 'formAddPostId'));
						echo $form->getError('forum_id');
						echo $form->getError('thread_id');
						echo $form->getError('body'); ?>
						<?php echo $txtMessage; ?>:
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<div style="height:20px;">
								<?php echo $messageBBCodeIconBar; ?>
							</div>
							<div>
								<?php echo $form->textArea('body', 60, 5, null, array('id' => 'postMessage'));	?>
							</div>
						</div><br/>
						<?php
						echo $form->inputHidden('forum_id', $forum_id);
						echo $form->inputHidden('thread_id', $thread_id);
						echo $form->submitButton('add_comment_submit', $txtAnswer);
						echo $form->submitButton('add_comment_advanced', $txtGoAdvancedBtn, array('onClick' => 'document.getElementById(\'formAddPostId\').action=\'/Forums/addPost/'.$forum_id.'/'.$thread_id.'\''));
					echo $form->end();
					?><br/>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif;?>
<br/>
</div>