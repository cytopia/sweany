<table class="forum" >
	<thead>
		<tr>
			<th style="font-size:12px;"><?php echo $language->title;?>, <?php echo $language->username;?></th>
			<th style="font-size:12px;"><?php echo $language->lastEntry;?></th>
			<th style="font-size:12px;"><?php echo $language->answers;?></th>
			<th style="font-size:12px;"><?php echo $language->views;?></th>
			<th style="font-size:12px;"><?php echo $language->forum;?></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($forumThreads as $thread):?>
		<?php $user_link  = ($userProfileLink) ? Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : $thread['username'];	?>
		<tr>
			<td>
				<div  style="font-weight:bold; font-size:12px;"><?php echo Html::l(Strings::shorten($thread['title'],30,true), 'Forums', 'showThread', array($thread['fk_forum_forums_id'], $thread['id'], $thread['seo_url']), array('style' => 'text-decoration: underline;')); ?></div>
				<span style="font-size:10px; line-height:120%;"><?php echo $user_link;?></span>
			</td>
			<td style="width:100px;">
				<div style="font-size:10px; line-height:120%; text-align:right;">
				<?php if ($thread['last_post_id']): ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($thread['last_post_username'], $userProfileCtl, $userProfileMethod, array($thread['last_post_user_id'])) : $thread['last_post_username'];?>
					<?php echo TimeHelper::getFormattedDate($thread['last_post_created'], 'd.m.Y', $language->today, $language->yesterday); ?> <span style="color:gray;"><?php echo date('H:i',strtotime($thread['last_post_created']));?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php else: ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : $thread['username'];?>	<?php echo TimeHelper::getFormattedDate($thread['created'], 'd.m.Y', $language->today, $language->yesterday); ?> <span style="color:gray"><?php echo date('H:i',strtotime($thread['created']));?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php endif; ?>
				</div>
			</td>
			<td style="text-align:center;"><?php echo $thread['count_posts']; ?></td>
			<td style="text-align:center;"><?php echo $thread['view_count']; ?></td>
			<td><?php echo Html::l($thread['forum_name'], 'Forums', 'showForum', array($thread['fk_forum_forums_id'])); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>