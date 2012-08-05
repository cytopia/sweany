<div>
	<div style="float:left;"><?php echo $userName; ?></div>
	<div style="float:right;"><?php echo Html::l($language->logout, $logoutCtl, $logoutMethod, array($sessionId));?></div>
	<div style="clear:both;"></div>
</div>