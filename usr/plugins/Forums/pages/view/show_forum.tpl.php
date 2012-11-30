<h1 class="forum"><?php echo $headline; ?></h1>

<?php if ( ($Forum->can_create || $isAdmin) ) : ?>
	<?php echo Html::l($language->newThread, 'Forums', 'addThread', array($Forum->id)); ?><br/>
<?php else : ?>
	<p><?php echo $language->cantNewThread; ?></p>
<?php endif; ?>


<table class="forum">
	<thead>
		<tr>
			<th colspan="6"><div class="forumNavi"><?php echo $navi; ?></div></th>
		</tr>
		<tr>
			<td colspan="6"></td>
		</tr>		<tr>
			<th></th>
			<th><?php echo $language->author; ?></th>
			<th><?php echo $language->threads; ?></th>
			<th><?php echo $language->lastPost; ?></th>
			<th><?php echo $language->replies; ?></th>
			<th><?php echo $language->views; ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="6">
				<?php echo $bOnlineUsers;?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($Forum->Thread as $Thread): ?>
			<?php
				if ( $Thread->post_count > 0 )
				{
					$timestamp		= $Thread->last_post_created;
					$last_date		= TimeHelper::date($date_format, $timestamp);
					$last_time		= TimeHelper::date($time_format, $timestamp);
					$last_user		= ($Thread->LastPost->fk_user_id > 0) ? $Thread->LastPost->username : 'anonymous';

					if ( ($userProfileLink) )
						$last_user_link	= ($Thread->LastPost->fk_user_id > 0) ? Html::l($last_user, $userProfileCtl, $userProfileMethod, array($Thread->LastPost->fk_user_id > 0)) : $last_user;
					else
						$last_user_link	= $last_user;
				}
				else
				{
					$last_date		= '';
					$last_time		= '';
					$last_user		= '';
					$last_user_link = '';
				}
				$sticky		= ($Thread->is_sticky) ? Html::img('/plugins/Forums/img/threads/is_sticky.png', $language->threadIsSticky, array('title' => $language->threadIsSticky)) : '';
				$locked		= ($Thread->is_locked) ? Html::img('/plugins/Forums/img/threads/is_locked.png', $language->threadIsLocked, array('title' => $language->threadIsLocked)) : '';
				$closed		= ($Thread->is_closed) ? Html::img('/plugins/Forums/img/threads/is_closed.png', $language->threadIsClosed, array('title' => $language->threadIsClosed)) : '';
				$timestamp	= $Thread->created;
				$date		= TimeHelper::date($date_format, $timestamp);
				$time		= TimeHelper::date($time_format, $timestamp);

				$author_name= ($Thread->fk_user_id > 0) ? $Thread->username : 'anonymous';
				$author_link= ($userProfileLink) ? Html::l($author_name, $userProfileCtl, $userProfileMethod, array($Thread->fk_user_id)) : $author_name;
			?>
			<tr>
				<td style="width:20px;"><div style="float:left;"><?php echo $sticky.$locked.$closed; ?></div></td>
				<td style="width:120px;">
					<div class="forumUsername"><?php echo $author_link; ?></div>
					<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
				</td>
				<td>
					<div class="forumThreadTitleLink"><?php echo Html::l(Strings::shorten($Thread->title, 70, true), 'Forums', 'showThread', array($Forum->id, $Thread->id, $Thread->seo_url)); ?></div>
					<div class="forumThreadBody"><?php echo Strings::removeTags(substr($Thread->body,0,20)).'...'; ?></div>
				</td>
				<td style="width:120px;">
					<div class="forumEntryTime"><?php echo $last_date.' '.$last_time; ?></div>
					<div class="forumUsername"><?php echo $last_user_link; ?></div>
				</td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $Thread->post_count; ?></td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $Thread->view_count; ?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>