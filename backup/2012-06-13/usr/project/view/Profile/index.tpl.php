<HR>
<HR>
<HR>
<HR>
<?php echo $peter ?>

<div>
<?php echo  $form->start('Form_ProfSearch') ?>

<?php echo  $form->fieldSetStart('Search Profiles') ?>
<?php echo  $form->getError('name') ?>
<?php echo  $form->label('name','Profile Name') ?>
<?php echo  $form->inputField( 'name').BR ?>
<?php echo  $form->submitButton('submit_Form_ProfSearch','Suchen') ?>
<?php echo  $form->fieldSetEnd() ?>

<?php echo  $form->end() ?>
</div>


<HR>
<HR>
<HR>
