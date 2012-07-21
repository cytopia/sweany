﻿<?php
$activeUsers = array();
foreach ($LoggedInOnlineUsers as $onlineUser)
	$activeUsers[] = $html->l($onlineUser['username'], $userProfileCtl, $userProfileMethod, array($onlineUser['id']));

?>
<table class="forum">
	<thead>
		<tr>
			<th></th>
			<th>Forum</th>
			<th style="width:250px;"><?php echo $txtLastEntry; ?></th>
			<th style="width:50px;"><?php echo $txtThreads; ?></th>
			<th style="width:50px;"><?php echo $txtPosts; ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="5">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="5">
				<span style="font-weight:bold;"><?php echo $txtCurrentOnline; ?>:</span> <?php echo $countOnlineUsers; ?> ( <?php echo $countLoggedInOnlineUsers;?> <?php echo ($countLoggedInOnlineUsers==1)?$txtRegisteredUser:$txtRegisteredUsers;?> / <?php echo $countAnonymousOnlineUsers; ?> <?php echo ($countAnonymousOnlineUsers==1)?$txtGuestUser:$txtGuestUsers;?> )
				<br/>
				<?php echo implode(', ', $activeUsers); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($categories as $category): ?>
			<tr>
				<th colspan="5"><div class="forumCategoryName"><?php echo $category['name']; ?></div></th>
			</tr>
			<?php foreach ($category['forums'] as $forum):?>
				<?php
				if ( $forum['thread_count'] > 0 )
				{
					$last_entry_created 	= $forum['last_thread_created'];
					$last_entry_title		= Strings::shorten($forum['last_thread_title'],40, true);
					$last_entry_user		= ($forum['last_thread_user_id'] > 0) ? $forum['last_thread_username'] : 'anonymous';
					$last_entry_user_link	= ($forum['last_thread_user_id'] > 0) ? $html->l($last_entry_user, $userProfileCtl, $userProfileMethod, array($forum['last_thread_user_id'])) : $last_entry_user;

					$last_entry_thread_id	= $forum['last_thread_id'];

					if ( $forum['post_count'] > 0 )
					{
						if ( $forum['last_thread_created'] < $forum['last_post_created'] )
						{
							$last_entry_created 	= $forum['last_post_created'];
							$last_entry_title		= Strings::shorten($forum['last_post_title'],40, true);
							$last_entry_user		= ($forum['last_post_user_id'] > 0) ? $forum['last_post_username'] : 'anonymous';
							$last_entry_user_link	= ($forum['last_post_user_id'] > 0) ? $html->l($last_entry_user, $userProfileCtl, $userProfileMethod, array($forum['last_post_user_id'])) : $last_entry_user;

							$last_entry_thread_id	= $forum['last_post_thread_id'];
						}
					}

					$timestamp	= strtotime($last_entry_created);
					$date		= date($date_format, $timestamp);
					$time		= date($time_format, $timestamp);
				}
				else
				{
					$last_entry_created 	= '';
					$last_entry_title		= '';
					$last_entry_user		= '';
					$last_entry_user_link	= '';
					$last_entry_thread_id	= '';
					$date					= '';
					$time					= '';
				}

				$icon	= $html->img('/img/packages/forum/'.$forum['icon']);

				?>
				<tr>
					<td><?php echo $icon; ?></td>
					<td>
						<div class="forumForumLink">
							<?php echo $html->l($forum['name'], 'Forums', 'showForum', array($forum['id'], $forum['seo_url'])); ?>
						</div>
						<p><?php echo $forum['description'];?></p>
					</td>
					<td>
						<div class="forumEntryLink"><?php echo $html->l($last_entry_title, 'Forums', 'showThread', array($forum['id'], $last_entry_thread_id, $forum['last_thread_seo_url'])); ?></div>
						<div class="forumUsername"><?php echo ($last_entry_user_link) ? $txtCreatedBy : ''; ?> <?php echo $last_entry_user_link; ?></div>
						<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
					</td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $forum['thread_count']; ?></td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $forum['post_count']; ?></td>
				</tr>
			<?php endforeach;?>
		<?php endforeach;?>
	</tbody>
</table>