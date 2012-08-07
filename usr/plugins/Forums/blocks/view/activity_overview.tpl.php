<?php
function getFormattedDate($mysqlDateTime, $txtToday, $txtYesterday)
{
	$TODAY		= date('Ymd');
	$YESTERDAY	= date('Ymd', time()-86400);	// (60*60*24)

	$checkDate = date('Ymd',strtotime($mysqlDateTime));

	if ( $checkDate == $TODAY )
		return $txtToday;
	else if ( $checkDate == $YESTERDAY )
		return $txtYesterday;
	else
		return date('d.m.Y',strtotime($mysqlDateTime));
}
function getFormattedTime($mysqlDateTime)
{
	return date('H:i',strtotime($mysqlDateTime));
}
?>
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
		<tr>
			<td>
				<div  style="font-weight:bold; font-size:12px;"><?php echo Html::l(Strings::shorten($thread['title'],30,true), 'Forums', 'showThread', array($thread['fk_forum_forums_id'], $thread['id'], $thread['seo_url']), array('style' => 'text-decoration: underline;')); ?></div>
				<span style="font-size:10px; line-height:120%;"><?php echo Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])); ?></span>
			</td>
			<td style="width:100px;">
				<div style="font-size:10px; line-height:120%; text-align:right;">
				<?php if ($thread['last_post_id']): ?>
					<?php echo getFormattedDate($thread['last_post_created'], $language->today, $language->yesterday); ?> <span style="color:gray;"><?php echo getFormattedTime($thread['last_post_created']);?></span><br/>
					<?php echo $language->by;?> <?php echo Html::l($thread['last_post_username'], $userProfileCtl, $userProfileMethod, array($thread['last_post_user_id'])); ?>
				<?php else: ?>
					<?php echo getFormattedDate($thread['created'], $language->today, $language->yesterday); ?> <span style="color:gray"><?php echo getFormattedTime($thread['created']);?></span><br/>
					<?php echo $language->by;?> <?php echo Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])); ?>
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