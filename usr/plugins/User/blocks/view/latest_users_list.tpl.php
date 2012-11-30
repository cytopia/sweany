<ul>
	<?php $index	= 0; ?>
	<?php $even		= true; ?>
	<?php foreach ($users as $user):?>
		<?php
			$even = ($index%2 == 0);
			$class= ($even) ? 'block_user_latest_list_row_even' : 'block_user_latest_list_row_odd';
			$index++;
		?>
		<li class="<?php echo $class;?>">
			<?php echo Html::img('/img/member.png', $user->username, array('title' => $user->username)); ?>
			<span class="block_user_latest_list_text">
				<?php echo Html::l($user->username, 'User', 'show', array($user->id), array('title' => $user->username)); ?>
			</span>
		</li>
	<?php endforeach; ?>
</ul>