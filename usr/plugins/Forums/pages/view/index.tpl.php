<table class="forum">
	<thead>
		<tr>
			<th></th>
			<th><?php echo $language->forum; ?></th>
			<th style="width:250px;"><?php echo $language->lastEntry; ?></th>
			<th style="width:50px;"><?php echo $language->threads; ?></th>
			<th style="width:50px;"><?php echo $language->posts; ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="5">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="5">
				<?php echo $blocks['onlineUsers'];?>
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
					$last_entry_user_link	= ($forum['last_thread_user_id'] > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($forum['last_thread_user_id'])) : $last_entry_user;

					$last_entry_thread_id	= $forum['last_thread_id'];

					if ( $forum['post_count'] > 0 )
					{
						if ( $forum['last_thread_created'] < $forum['last_post_created'] )
						{
							$last_entry_created 	= $forum['last_post_created'];
							$last_entry_title		= Strings::shorten($forum['last_post_title'],40, true);
							$last_entry_user		= ($forum['last_post_user_id'] > 0) ? $forum['last_post_username'] : 'anonymous';
							$last_entry_user_link	= ($forum['last_post_user_id'] > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($forum['last_post_user_id'])) : $last_entry_user;

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

				$icon	= Html::img('/img/packages/forum/'.$forum['icon']);

				?>
				<tr>
					<td><?php echo $icon; ?></td>
					<td>
						<div class="forumForumLink">
							<?php echo Html::l($forum['name'], 'Forums', 'showForum', array($forum['id'], $forum['seo_url'])); ?>
						</div>
						<p><?php echo $forum['description'];?></p>
					</td>
					<td>
						<div class="forumEntryLink"><?php echo Html::l($last_entry_title, 'Forums', 'showThread', array($forum['id'], $last_entry_thread_id, $forum['last_thread_seo_url'])); ?></div>
						<div class="forumUsername"><?php echo ($last_entry_user_link) ? $language->by : ''; ?> <?php echo $last_entry_user_link; ?></div>
						<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
					</td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $forum['thread_count']; ?></td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $forum['post_count']; ?></td>
				</tr>
			<?php endforeach;?>
		<?php endforeach;?>
	</tbody>
</table>