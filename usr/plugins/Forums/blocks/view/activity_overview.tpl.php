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
	<?php foreach ($Threads as $Thread):?>

		<?php $user_link	= ($userProfileLink) ? Html::l($Thread->User->username, $userProfileCtl, $userProfileMethod, array($Thread->User->id)) : $Thread->User->username;	?>
		<tr>
			<td>
				<div  style="font-weight:bold; font-size:12px;"><?php echo Html::l(Strings::shorten($Thread->title, 30, true), 'Forums', 'showThread', array($Thread->Forum->id, $Thread->id, $Thread->seo_url), array('style' => 'text-decoration: underline;')); ?></div>
				<span style="font-size:10px; line-height:120%;"><?php echo $user_link;?></span>
			</td>
			<td style="width:100px;">
				<div style="font-size:10px; line-height:120%; text-align:right;">
				<?php if (isset($Thread->LastPost->id)): ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($Thread->LastPost->username, $userProfileCtl, $userProfileMethod, array($Thread->LastPost->fk_user_id)) : $Thread->LastPost->username;?>
					<?php echo TimeHelper::getFormattedDate('d.m.Y', $Thread->LastPost->created, array($language->today, $language->yesterday)); ?> <span style="color:gray;"><?php echo TimeHelper::date('H:i',$Thread->LastPost->created);?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php else: ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($Thread->User->username, $userProfileCtl, $userProfileMethod, array($Thread->User->id)) : $Thread->User->username;?>
					<?php echo TimeHelper::getFormattedDate('d.m.Y', $Thread->created, array($language->today, $language->yesterday)); ?> <span style="color:gray"><?php echo TimeHelper::date('H:i',$Thread->created);?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php endif; ?>
				</div>
			</td>
			<td style="text-align:center;"><?php echo $Thread->post_count; ?></td>
			<td style="text-align:center;"><?php echo $Thread->view_count; ?></td>
			<td><?php echo Html::l($Thread->Forum->name, 'Forums', 'showForum', array($Thread->Forum->id, $Thread->Forum->seo_url)); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>