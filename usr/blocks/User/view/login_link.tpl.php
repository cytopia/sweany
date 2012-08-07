<?php echo $language->welcome; ?><br/>
<?php echo $language->site_name; ?><br/><br/>
<?php echo Html::l($language->login, $loginCtl, $loginMethod); ?><br/>
<?php echo $language->or;?> <?php echo Html::l($language->signup, $signupCtl, $signupMethod); ?>

