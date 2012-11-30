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
		<?php foreach ($Categories as $Category): ?>
			<tr>
				<th colspan="5"><div class="forumCategoryName"><?php echo $Category->name; ?></div></th>
			</tr>
			<?php foreach ($Category->Forum as $Forum):?>
				<?php
				if ( $Forum->thread_count > 0 )
				{
					$last_entry_created 	= $Forum->LastThread->created;
					$last_entry_title		= Strings::shorten($Forum->LastThread->title, 40, true);
					$last_entry_user		= ($Forum->LastThread->fk_user_id > 0) ? $Forum->LastThread->username : 'anonymous';

					if ( ($userProfileLink) )
						$last_entry_user_link	= ($Forum->LastThread->fk_user_id > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($Forum->LastThread->fk_user_id)) : $last_entry_user;
					else
						$last_entry_user_link	= $last_entry_user;

					$last_entry_thread_id	= $Forum->LastThread->id;
					$last_entry_seo_url		= $Forum->LastThread->seo_url;

					if ( $Forum->LastThread->post_count > 0 )
					{
						if ( $Forum->LastThread->created <= $Forum->LastThread->last_post_created )
						{
							$last_entry_created 	= $Forum->LastThread->last_post_created;
							$last_entry_user		= ($Forum->LastThread->post_user_id > 0) ? $Forum->LastThread->post_username : 'anonymous';

							if ( ($userProfileLink) )
								$last_entry_user_link	= ($Forum->LastThread->post_user_id > 0) ? Html::l($last_entry_user, $userProfileCtl, $userProfileMethod, array($Forum->LastThread->post_user_id)) : $last_entry_user;
							else
								$last_entry_user_link	= $last_entry_user;
						}
					}

					$timestamp	= $last_entry_created;
					$date		= TimeHelper::date($date_format, $timestamp);
					$time		= TimeHelper::date($time_format, $timestamp);
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

				$icon	= Html::img('/plugins/Forums/img/'.$Forum->icon);

				?>
				<tr>
					<td><?php echo $icon; ?></td>
					<td>
						<div class="forumForumLink">
							<?php echo Html::l($Forum->name, 'Forums', 'showForum', array($Forum->id, $Forum->seo_url)); ?>
						</div>
						<p><?php echo $Forum->description;?></p>
					</td>
					<td>
						<div class="forumEntryLink"><?php echo Html::l($last_entry_title, 'Forums', 'showThread', array($Forum->id, $last_entry_thread_id, $last_entry_seo_url)); ?></div>
						<div class="forumUsername"><?php echo ($last_entry_user_link) ? $language->by : ''; ?> <?php echo $last_entry_user_link; ?></div>
						<div class="forumEntryTime"><?php echo $date.' '.$time; ?></div>
					</td>
					<td style="text-align:center;vertical-align:middle;"><?php echo $Forum->thread_count; ?></td>
					<td style="text-align:center;vertical-align:middle;"><?php echo isset($Forum->post_count) ? $Forum->post_count : 0; ?></td>
				</tr>
			<?php endforeach;?>
		<?php endforeach;?>
	</tbody>
</table>