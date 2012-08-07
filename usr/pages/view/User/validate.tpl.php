
<?php if ($success):?>
	<h2><?php echo $language->accountActivatedTitle; ?></h2>

	<p><?php echo $language->accountActivatedBody; ?> <?php echo Html::l($language->here, 'User', 'login'); ?></p>
<?php else: ?>
	<h2><?php echo $language->invalidKeyTitle; ?></h2>

	<p><?php echo $language->invalidKeyBody; ?></p>
<?php endif; ?>
