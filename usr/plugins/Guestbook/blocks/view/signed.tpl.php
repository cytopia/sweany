<?php echo Form::start('guestbook_signed');?>
	<?php echo Form::fieldsetStart($language->fieldset_name); ?>
		<?php echo Form::getError('avatar');?>
		<?php echo Form::label('avatar', $language->avatar)?>
		<?php echo Form::inputField('avatar', null, array('id' => 'avatarField')); ?> <a href="/plugins/Guestbook/html/select_avatar.php" onClick="return popup(this, 'select_avatar');"><?php echo $language->choose;?></a><br/><br/>

		<?php echo Form::getError('text');?>
		<?php echo Form::label('text', $language->entry.' (*)')?>
		<?php echo Form::editor('text', null, 80, 10); ?><br/>

		<?php echo Form::label('submit', '')?>
		<?php echo Form::submitButton('submit', $language->add);?>
	<?php echo Form::fieldsetEnd(); ?>
<?php echo Form::end();?>
