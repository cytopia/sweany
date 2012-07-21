<?php echo $form->start('form_add_contact'); ?>

	<?php echo $form->fieldSetStart('Fragen, Ideen oder Kritik?'); ?>

		<p>Damit deine Anfrage schnellstm&ouml;glich beantwortet werden kann, w&auml;hle bitte den passenden Betreff aus.</p>
		<br/>

		<?php echo $form->getError('name'); ?>
		<?php echo $form->label('name', 'Name:'); ?>
		<?php echo $form->inputField('name', NULL, array('size' => 20)).BR.BR; ?>

		<?php echo $form->getError('email'); ?>
		<?php echo $form->label('email', 'Email:'); ?>
		<?php echo $form->inputField('email', NULL, array('size' => 20)).BR.BR; ?>

		<?php echo $form->getError('subject'); ?>
		<?php echo $form->label('subject', 'Betreff:'); ?>
		<?php echo $form->selectBox('subject', $subject).BR.BR; ?>

		<?php echo $form->getError('message'); ?>
		<?php echo $form->label('message', 'Nachricht:'); ?>
		<?php echo $form->textArea('message', 40, 8); ?>
		<br/><br/>

		<?php echo $form->label('contact_submit', ''); ?>
		<?php echo $form->submitButton('contact_submit', 'Nachricht senden'); ?>

	<?php echo $form->fieldSetEnd().BR; ?>
<?php echo $form->end(); ?>

