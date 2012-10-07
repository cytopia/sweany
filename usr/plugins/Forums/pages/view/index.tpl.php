<table class="forum">
	<thead>
		<tr>
			<th></th>
			<th><?php echo $language->forum; ?></th>
			<th style="width:250px;"><?php echo $language->lastEntry; ?></th>
			<th style="width:50px;"><?php echo $language->threads; ?></th>
			<th style="width:50px;"><?php echo $language->posts; ?></th>
		</tr>
		<tr>
			<td colspan="5"></td>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th colspan="5">&nbsp;</th>
		</tr>
		<tr>
			<td colspan="5">
				<?php echo $bOnlineUsers;?>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?php foreach ($data as $row): ?>
			<tr>
				<th colspan="5"><div class="forumCategoryName"><?php echo $row->Category->name; ?></div></th>
			</tr>
			<?php foreach ($row->Forum as $forum):?>
				<?php
				if ( $forum->thread_count > 0 )
				{
					$lastThread				= $forum->LastThread[0];

					$last_entry_created 	= $lastThread->created;
					$last_entry_title		= Strings::shorten($lastThread->title,40, true);
					$last_entry_user		= ($lastThread->fk_user_id > 0) ? $lastThread->username : 'anonymous';

					if ( ($userProfileLink) )
						$last_entry_user_link	= ($lastThread->fk_user_id > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($lastThread->fk_user_id)) : $last_entry_user;
					else
						$last_entry_user_link	= $last_entry_user;

					$last_entry_thread_id	= $lastThread->id;
					$last_entry_seo_url		= $lastThread->seo_url;

					if ( $lastThread->post_count > 0 )
					{
						if ( $lastThread->created <= $lastThread->last_post_created )
						{
							$last_entry_created 	= $lastThread->last_post_created;
							$last_entry_title		= Strings::shorten($lastThread->post_title, 40, true);
							$last_entry_user		= ($lastThread->post_user_id > 0) ? $lastThread->post_username : 'anonymous';

							if ( ($userProfileLink) )
								$last_entry_user_link	= ($lastThread->post_user_id > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($lastThread->post_user_id)) : $last_entry_user;
							else
								$last_entry_user_link	= $last_entry_user;

							//$last_entry_thread_id	= $forum['last_post_thread_id'];
							//$last_entry_seo_url		= $forum['last_post_seo_url'];
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
					$last_entry_seo_url		= '';
					$date					= '';
					$time					= '';
				}

				$icon	= Html::img('/plugins/Forums/img/'.$forum->icon);

				?>
				<tr>
					<td><?php echo $icon; ?></td>
					<td>
						<div class="forumForumLink">
							<?php echo Html::l($forum->name, 'Forums', 'showForum', array($forum->id, $forum->seo_url)); ?>
						</div>
						<p><?php echo $forum->description;?></p>
					</td>
					<td>
						<div class="forumEntryLink"><?php echo Html::l($last_entry_title, 'Forums', 'showThread', array($forum->id, $last_entry_thread_id, $last_entry_seo_url)); ?></div>
						<div class="forumUsername"><?php echo ($last_entry_user_link) ? $language->by : ''; ?> <?php echo $last_entry_user_link; ?></div>
						<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
					</td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $forum->thread_count; ?></td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $lastThread->post_count; ?></td>
				</tr>
			<?php endforeach;?>
		<?php endforeach;?>
	</tbody>
</table>
