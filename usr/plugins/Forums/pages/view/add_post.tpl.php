<h1 class="forum"><?php echo $headline; ?></h1>


<?php if (!$user->isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $language->reply; ?></div>
	<p><?php echo $language->createLoginNote; ?> <?php echo Html::l($language->here, $userLoginCtl,$userLoginMethod);?> <?php echo $language->or;?> <?php echo Html::l($language->registerFree, $userRegisterCtl,$userRegisterMethod);?></p>
<?php else: ?>

	<?php if (isset($preview)): ?>
		<table class="forum">
			<thead>
				<tr>
					<th><?php echo $language->preview?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="forumPostTitle"><?php echo $postPreview['title']; ?></div>
						<div class="forumPostBody"><?php echo Bbcode::parse($postPreview['body']); ?></div>
					</td>
				</tr>
			</tbody>
		</table><br/>
	<?php endif; ?>

	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><div class="forumNavi"><?php echo $navi; ?></div></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2"></td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td style="width:150px;"></td>
				<td>

					<div style="font-weight:bold; font-size:14px;"><?php echo $language->reply; ?></div><br/>
					<?php
					echo Form::start('form_add_post');
						echo Form::getError('forum_id');
						echo $language->title.':<br/>';
						echo Form::getError('title');
						echo Form::inputField('title', NULL, array('size' => 50)).BR;
						echo $language->message.':';
						echo Form::getError('body');	?>
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<?php echo Form::editor('body', null, 60, 14, array('id' => 'postBody')); ?>
						</div><br/>
						<?php
						echo Form::inputHidden('forum_id', $Thread->Forum->id);
						echo Form::inputHidden('thread_id', $Thread->id);
						echo Form::submitButton('add_post_submit', $language->answer);
						echo Form::submitButton('add_post_preview', $language->preview);
					echo Form::end();
					?><br/>
				</td>
			</tr>
		</tbody>
	</table><br/>


	<?php /****************************** DISPLAY POSTS IN REVERSE ORDER ***************************/ ?>
	<table class="forum">
		<thead>
			<tr>
				<th colspan="2"><strong><?php echo $language->overview?></strong> <span style="font-weight:normal;">(<?php echo $language->latestFirst;?>)</span></th>
			</tr>
		</thead>
	</table><br/>

	<?php foreach ($entries as $entry): ?>
		<?php
			$date		= TimeHelper::date($date_format, $entry->created);
			$time		= TimeHelper::date($time_format, $entry->created);
		?>
		<table class="forum">
			<tbody>
				<tr>
					<th colspan="2"><?php echo $date.' '.$time; ?></th>
				</tr>
				<tr>
					<td style="width:150px;">
						<div class="forumUsername"><?php echo strlen($entry->username) ? $entry->username : 'anonymous'; ?></div><br/>
					</td>
					<td>
						<div class="forumPostBody">
							<?php echo Bbcode::parse($entry->body); ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	<?php endforeach; ?>
<?php endif;?>