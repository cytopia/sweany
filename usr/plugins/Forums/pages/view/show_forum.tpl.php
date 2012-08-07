<h1 class="forum"><?php echo $headline; ?></h1>

<?php if ( ($can_create || $isAdmin) ) : ?>
	<?php echo Html::l($language->newThread, NULL, 'addThread', array($forum_id)); ?><br/>
<?php else : ?>
	<p><?php echo $language->cantNewThread; ?></p>
<?php endif; ?>


<table class="forum">
	<thead>
		<tr>
			<th colspan="6"><div class="forumNavi"><?php echo $navi; ?></div></th>
		</tr>
		<tr>
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
				<?php echo $blocks['onlineUsers'];?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($threads as $thread): ?>
			<?php
				if ( $thread['count_posts'] > 0 )
				{
					$timestamp		= strtotime($thread['last_post_created']);
					$last_date		= date($date_format, $timestamp);
					$last_time		= date($time_format, $timestamp);
					$last_user		= ($thread['last_post_user_id'] > 0) ? $thread['last_post_username'] : 'anonymous';
					$last_user_link	= ($thread['last_post_user_id'] > 0) ? Html::l($last_user, $userProfileCtl, $userProfileMethod, array($thread['last_post_user_id'])) : $last_user;
				}
				else
				{
					$last_date		= '';
					$last_time		= '';
					$last_user		= '';
					$last_user_link = '';
				}
				$sticky		= ($thread['is_sticky']) ? Html::img('/img/packages/forum/threads/is_sticky.png', $language->threadIsSticky, array('title' => $language->threadIsSticky)) : '';
				$locked		= ($thread['is_locked']) ? Html::img('/img/packages/forum/threads/is_locked.png', $language->threadIsLocked, array('title' => $language->threadIsLocked)) : '';
				$closed		= ($thread['is_closed']) ? Html::img('/img/packages/forum/threads/is_closed.png', $language->threadIsClosed, array('title' => $language->threadIsClosed)) : '';
				$timestamp	= strtotime($thread['created']);
				$date		= date($date_format, $timestamp);
				$time		= date($time_format, $timestamp);
			?>
			<tr>
				<td style="width:20px;"><div style="float:left;"><?php echo $sticky.$locked.$closed; ?></div></td>
				<td style="width:120px;">
					<div class="forumUsername"><?php echo ($thread['fk_user_id']>0) ? Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : 'anonymous'; ?></div>
					<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
				</td>
				<td>
					<div class="forumThreadTitleLink"><?php echo Html::l(Strings::shorten($thread['title'], 70, true), 'Forums', 'showThread', array($forum_id, $thread['id'], $thread['seo_url'])); ?></div>
					<div class="forumThreadBody"><?php echo Strings::removeTags(substr($thread['body'],0,20)).'...'; ?></div>
				</td>
				<td style="width:120px;">
					<div class="forumEntryTime"><?php echo $last_date.' '.$last_time; ?></div>
					<div class="forumUsername"><?php echo $last_user_link; ?></div>
				</td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $thread['count_posts']; ?></td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $thread['view_count']; ?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>