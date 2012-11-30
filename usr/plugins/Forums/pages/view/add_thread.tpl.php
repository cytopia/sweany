<h1 class="forum"><?php echo $headline; ?></h1>


<?php if (!$user->isLoggedIn()):?>
	<br/>
	<div style="font-size:16px;"><?php echo $language->createThread; ?></div>
	<p><?php echo $language->createLoginNote; ?> <?php echo Html::l($language->here, $userLoginCtl,$userLoginMethod);?> <?php echo $language->or;?> <?php echo Html::l($language->registerFree, $userRegisterCtl,$userRegisterMethod);?></p>
<?php else: ?>

	<?php if (isset($preview)): ?>
		<table class="forum">
			<thead>
				<tr>
					<th><?php echo $language->preview; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<div class="forumPostTitle"><?php echo $threadPreview['title']; ?></div>
						<div class="forumPostBody"><?php echo Bbcode::parse($threadPreview['body']); ?></div>
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

					<div style="font-weight:bold; font-size:14px;"><?php echo $language->createThread; ?></div><br/>
					<?php
					echo Form::start('form_add_thread');
						echo Form::getError('forum_id');
						echo $language->title.':<br/>';
						echo Form::getError('title');
						echo Form::inputField('title', NULL, array('size' => 50)).BR;
						echo $language->message.':';
						echo Form::getError('body');	?>
						<div style="text-align:left; padding:8px; border:solid 1px black; width:505px; background-color:#FDFDFD;">
							<?php echo Form::editor('body', null, 60, 14, array('id' => 'postBody'))?>
						</div><br/>
						<?php
						echo Form::inputHidden('forum_id', $Forum->id);
						echo Form::submitButton('add_thread_submit', $language->create);
						echo Form::submitButton('add_thread_preview', $language->preview);
					echo Form::end();
					?><br/>
				</td>
			</tr>
		</tbody>
	</table>
<?php endif;?>