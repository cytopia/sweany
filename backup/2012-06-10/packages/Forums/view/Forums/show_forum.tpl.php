<div id="content_one_col">
<h1 class="forum"><?php echo $headline; ?></h1>

<?php if ( ($can_create || $isAdmin) ) : ?>
	<?php echo $html->l($txtNewThread, NULL, 'addThread', array($forum_id)); ?><br/>
<?php else : ?>
	<p><?php echo $txtCannotThread; ?></p>
<?php endif; ?>


<table class="forum">
	<thead>
		<tr>
			<th colspan="6"><?php echo $navi; ?></th>
		</tr>
		<tr>
			<th></th>
			<th><?php echo $txtAuthor; ?></th>
			<th><?php echo $txtThreads; ?></th>
			<th><?php echo $txtLastPost; ?></th>
			<th><?php echo $txtReplies; ?></th>
			<th><?php echo $txtViews; ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="6"></th>
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
					$last_user_link	= ($thread['last_post_user_id'] > 0) ? $html->l($last_user, $userProfileCtl, $userProfileMethod, array($thread['last_post_user_id'])) : $last_user;
				}
				else
				{
					$last_date		= '';
					$last_time		= '';
					$last_user		= '';
					$last_user_link = '';
				}
				$sticky		= ($thread['is_sticky']) ? $html->img('/img/packages/forum/threads/is_sticky.png', $txtThreadIsSticky, array('title' => $txtThreadIsSticky)) : '';
				$locked		= ($thread['is_locked']) ? $html->img('/img/packages/forum/threads/is_locked.png', $txtThreadIsLocked, array('title' => $txtThreadIsLocked)) : '';
				$closed		= ($thread['is_closed']) ? $html->img('/img/packages/forum/threads/is_closed.png', $txtThreadIsClosed, array('title' => $txtThreadIsClosed)) : '';
				$timestamp	= strtotime($thread['created']);
				$date		= date($date_format, $timestamp);
				$time		= date($time_format, $timestamp);
			?>
			<tr>
				<td style="width:20px;"><div style="float:left;"><?php echo $sticky.$locked.$closed; ?></div></td>
				<td style="width:120px;">
					<?php echo ($thread['fk_user_id']>0) ? $html->l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : 'anonymous'; ?><br/>
					<?php echo $date.' '.$time; ?><br/>
				</td>
				<td>
					<?php echo $html->l(Strings::shorten($thread['title'], 70, true), 'Forums', 'showThread', array($forum_id, $thread['id'], $thread['seo_url'])); ?><br/>
					<?php echo Strings::removeTags(substr($thread['body'],0,20)).'...'; ?>
				</td>
				<td style="width:120px;">
					<?php echo $last_date.' '.$last_time; ?><br/>
					<?php echo $last_user_link; ?>
				</td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $thread['count_posts']; ?></td>
				<td style="width:40px;text-align:center;vertical-align:middle;"><?php echo $thread['view_count']; ?></td>
			</tr>
		<?php endforeach;?>
	</tbody>
</table><br/>
</div>