<div class="plugin_user">
	<?php if ($exists): ?>
		<div class="plugin_user_details">
			<div class="plugin_user_username"><?php echo $user_name; ?></div>
		</div>
		<?php if ( !$is_me ):?>
			<?php if ($userWriteMessageLinkEnable):?>
				<div class="plugin_user_contact">
					<?php if ($userWriteMessageIconEnable): ?>
						<?php echo Html::img($userWriteMessageIconPath)?>
					<?php endif;?>
					<?php echo Html::l($language->writeMessage, $userWriteMessageCtl, $userWriteMessageMethod, array($user_id));?>
				</div>
			<?php endif; ?>
		<?php endif;?>
	<?php else: ?>
		<div class="plugin_user_details_not_found">
			<?php echo $language->userNotFound;?>
		</div>
	<?php endif;?>
</div>