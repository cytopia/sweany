<div class="<?php echo ($css) ? $css : 'forum';?>'" >
	<?php $even = true; ?>
	<?php $count= 0; ?>
	<?php foreach ($entries as $entry):?>
		<?php $even = ($count%2==0); ?>
		<div class="line forum_entry_row <?php echo ($even) ? 'forum_entry_row_even' : 'forum_entry_row_odd';?>">
			<div class="unit size1of9">
				<div class="line">
					<img src="/img/profiles/avatar/16x16/default.png" alt="" />
				</div>
			</div>

			<div class="unit size8of9">
				<div class="line">
					<div class="unit">
						<div class="forum_title">
							<?php if ($entry['last_post_id']): ?>
								<?php echo Html::l('Re: '.Strings::shorten($entry['title'],30,true), 'Forums', 'showThread', array($entry['fk_forum_forums_id'], $entry['id'], $entry['seo_url'])); ?>
							<?php else: ?>
								<?php echo Html::l(Strings::shorten($entry['title'],30,true), 'Forums', 'showThread', array($entry['fk_forum_forums_id'], $entry['id'], $entry['seo_url'])); ?>
							<?php endif;?>
						</div>
					</div>
				</div><!-- line -->

				<div class="line">
					<div class="unit">
						<div class="forum_body">
							<?php if ($entry['last_post_id']): ?>
								<?php echo Strings::shorten(Bbcode::remove($entry['last_post_body']),70,true); ?>
							<?php else: ?>
								<?php echo Strings::shorten(Bbcode::remove($entry['body']),70,true); ?>
							<?php endif;?>
						</div>
					</div>
				</div><!-- line -->

				<div class="line">
					<div class="unit">
						<div class="forum_user_and_date">
							<?php if ($entry['last_post_id']): ?>
								<span class="forum_date">
									<?php echo TimeHelper::getFormattedDate($entry['last_post_created'], 'd.m.Y', $language->today, $language->yesterday); ?>
								</span>
								<span class="forum_time">
									 <?php echo date('H:i',strtotime($entry['last_post_created']));?>
								</span>
								<span class="forum_user">
									<?php $last_user_link = ($userProfileLink) ? Html::l($thread['username'], $userProfileCtl, $userProfileMethod, array($thread['fk_user_id'])) : $entry['last_post_username'];?>
									<?php echo TimeHelper::getFormattedDate($thread['created'], 'd.m.Y', $language->today, $language->yesterday); ?> <span style="color:gray"><?php echo date('H:i',strtotime($thread['created']));?></span><br/>
									<?php echo $language->by;?> <?php echo $last_user_link;  ?>
								</span>
							<?php else: ?>
								<span class="forum_date">
									<?php echo TimeHelper::getFormattedDate($entry['created'], 'd.m.Y', $language->today, $language->yesterday); ?>
								</span>
								<span class="forum_time">
									<?php echo date('H:i',strtotime($entry['created']));?>
								</span>
								<span class="forum_user">
									<?php $last_user_link = ($userProfileLink) ? Html::l($entry['username'], $userProfileCtl, $userProfileMethod, array($entry['fk_user_id'])) : $entry['username'];?>
									<?php echo $language->by;?> <?php echo $last_user_link; ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				</div><!-- line -->

				<div class="line">
					<div class="unit">
						<div class="forum_name">
							<?php echo Html::l(Strings::shorten($entry['forum_name'],70,true), 'Forums', 'showForum', array($entry['fk_forum_forums_id'])); ?>
						</div>
					</div>
				</div><!-- line -->

			</div><!-- unit -->

		</div><!-- line -->
		<div class="forum_entry_divider"></div>
		<?php $count++;?>
	<?php endforeach; ?>
</div>