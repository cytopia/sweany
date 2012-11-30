<?php echo Form::start('guestbook_unsigned');?>
	<?php echo Form::fieldsetStart($language->fieldset_name); ?>

		<?php /* inputfield (hidden) for bot protection */ ?>
		<?php echo Form::getError('username'); ?>
		<?php echo Form::inputField('username', null, array('style' => 'display:none;')); ?>
		
		<?php echo Form::getError('author');?>
		<?php echo Form::label('author', $language->author.' (*)')?>
		<?php echo Form::inputField('author'); ?><br/><br/>

		<?php echo Form::label('email', $language->email)?>
		<?php echo Form::inputField('email'); ?><br/><br/>

		<?php echo Form::getError('avatar');?>
		<?php echo Form::label('avatar', $language->avatar)?>
		<?php echo Form::inputField('avatar', null, array('id' => 'avatarField')); ?> <a href="/plugins/Guestbook/html/select_avatar.php" onClick="return popup(this, 'select_avatar');"><?php echo $language->choose;?></a><br/><br/>

		<?php echo Form::getError('text');?>
		<?php echo Form::label('text', $language->entry.' (*)')?>
		<?php echo Form::editor('text', null, 80, 10); ?><br/>

		<?php echo Form::label('captcha_pic', $language->captcha.' (*)')?>
		<?php echo Captcha::img('captcha');?><br/>

		<?php echo Form::getError('captcha');?>
		<?php echo Form::label('info', '')?>
		<?php echo $language->captcha_text;?><br/>
		<?php echo Form::label('captcha', '')?>
		<?php echo Form::inputField('captcha');?><br/><br/>

		<?php echo Form::label('submit', '')?>
		<?php echo Form::submitButton('submit', $language->add);?>
	<?php echo Form::fieldsetEnd(); ?>
<?php echo Form::end();?>
