<?php echo Form::start('form_add_contact'); ?>

	<?php echo Form::fieldSetStart($language->form_legend); ?>

		<p><?php echo $language->form_note; ?></p><br/>

		<?php echo Form::getError('name'); ?>
		<?php echo Form::label('name', $language->form_label_name); ?>
		<?php echo Form::inputField('name').'<br/><br/>'; ?>

		<?php echo Form::getError('email'); ?>
		<?php echo Form::label('email', $language->form_label_email); ?>
		<?php echo Form::inputField('email').'<br/><br/>'; ?>

		<?php echo Form::getError('subject'); ?>
		<?php echo Form::label('subject', $language->form_label_subject); ?>
		<?php echo Form::selectBox('subject', $subject).'<br/><br/>'; ?>

		<?php echo Form::getError('message'); ?>
		<?php echo Form::label('message', $language->form_label_message); ?>
		<?php echo Form::textArea('message').'<br/><br/>'; ?>

		<?php echo Form::label('contact_submit', ''); ?>
		<?php echo Form::submitButton('contact_submit', $language->form_btn_submit); ?>

	<?php echo Form::fieldSetEnd(); ?>
<?php echo Form::end(); ?>