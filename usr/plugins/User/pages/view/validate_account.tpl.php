<?php if ($success):?>
	<h2><?php echo $language->accountActivated;?></h2>
	<p><?php echo $language->accountActivatedText;?></p>
	<?php echo Html::l($language->loginHere, 'User', 'login')?>
<?php else: ?>
	<h2><?php echo $language->invalidKey;?></h2>
	<p><?php echo $language->invalidKeyText;?></p>
<?php endif; ?>
