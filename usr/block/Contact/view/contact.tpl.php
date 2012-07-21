<?php echo $form->start('form_add_contact'); ?>

	<?php echo $form->fieldSetStart($language->form_legend); ?>

		<p><?php echo $language->form_note; ?></p><br/>

		<?php echo $form->getError('name'); ?>
		<?php echo $form->label('name', $language->form_label_name); ?>
		<?php echo $form->inputField('name').BR.BR; ?>

		<?php echo $form->getError('email'); ?>
		<?php echo $form->label('email', $language->form_label_email); ?>
		<?php echo $form->inputField('email').BR.BR; ?>

		<?php echo $form->getError('subject'); ?>
		<?php echo $form->label('subject', $language->form_label_subject); ?>
		<?php echo $form->selectBox('subject', $subject).BR.BR; ?>

		<?php echo $form->getError('message'); ?>
		<?php echo $form->label('message', $language->form_label_message); ?>
		<?php echo $form->textArea('message').BR.BR; ?>

		<?php echo $form->label('contact_submit', ''); ?>
		<?php echo $form->submitButton('contact_submit', $language->form_btn_submit); ?>

	<?php echo $form->fieldSetEnd(); ?>
<?php echo $form->end(); ?>
