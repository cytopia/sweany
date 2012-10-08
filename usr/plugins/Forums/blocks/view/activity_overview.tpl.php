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
	<?php foreach ($data as $row):?>
		<?php $LastPost		= isset($row->LastPost[0]) ? $row->LastPost[0] : null;?>
		<?php $user_link	= ($userProfileLink) ? Html::l($row->User->username, $userProfileCtl, $userProfileMethod, array($row->User->id)) : $row->User->username;	?>
		<tr>
			<td>
				<div  style="font-weight:bold; font-size:12px;"><?php echo Html::l(Strings::shorten($row->Thread->title,30,true), 'Forums', 'showThread', array($row->Forum->id, $row->Thread->id, $row->Thread->seo_url), array('style' => 'text-decoration: underline;')); ?></div>
				<span style="font-size:10px; line-height:120%;"><?php echo $user_link;?></span>
			</td>
			<td style="width:100px;">
				<div style="font-size:10px; line-height:120%; text-align:right;">
				<?php if (isset($LastPost->id)): ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($LastPost->username, $userProfileCtl, $userProfileMethod, array($LastPost->fk_user_id)) : $LastPost->username;?>
					<?php echo TimeHelper::getFormattedDate($LastPost->created, 'd.m.Y', $language->today, $language->yesterday); ?> <span style="color:gray;"><?php echo date('H:i',strtotime($LastPost->created));?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php else: ?>
					<?php $last_user_link = ($userProfileLink) ? Html::l($row->User->username, $userProfileCtl, $userProfileMethod, array($row->User->id)) : $row->User->username;?>
					<?php echo TimeHelper::getFormattedDate($row->Thread->created, 'd.m.Y', $language->today, $language->yesterday); ?> <span style="color:gray"><?php echo date('H:i',strtotime($row->Thread->created));?></span><br/>
					<?php echo $language->by;?> <?php echo $last_user_link;?>
				<?php endif; ?>
				</div>
			</td>
			<td style="text-align:center;"><?php echo $row->Thread->post_count; ?></td>
			<td style="text-align:center;"><?php echo $row->Thread->view_count; ?></td>
			<td><?php echo Html::l($row->Forum->name, 'Forums', 'showForum', array($row->Forum->id, $row->Forum->seo_url)); ?></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>