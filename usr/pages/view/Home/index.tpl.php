<h1>Home Page</h1>

<h2>Test User Accounts</h2>
<ul style="margin-left:20px;">
	<li>admin : admin</li>
	<li>demo1 : demo1</li>
	<li>demo2 : demo2</li>
	<li>demo3 : demo3</li>
	<li>demo4 : demo4</li>
	<li>demo5 : demo5</li>
</ul>
<br/><br/>

<?php if ( $user->isLoggedIn() ): ?>
	<?php echo Html::l(t('Messages'), 'Message', 'inbox'); ?><br/>
	<?php echo Html::l(t('Edit Data'), 'User', 'editData'); ?><br/>
	<?php echo Html::l(t('Settings'), 'User', 'settings'); ?><br/>
	<br/><br/>

<?php endif;?>

<?php echo $bForumThreads; ?><br/>
