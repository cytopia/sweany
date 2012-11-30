
<?php
$activeUsers = array();
foreach ($LoggedInOnlineUsers as $onlineUser)
{
	$activeUsers[] = ($userProfileLink) ? Html::l($onlineUser->username, $userProfileCtl, $userProfileMethod, array($onlineUser->id)) : $onlineUser->username;
}
?>
<span style="font-weight:bold;"><?php echo $language->currOnlineUsers; ?>: </span>
<?php echo $countOnlineUsers; ?> (
 <?php echo $countLoggedInOnlineUsers;?>
 <?php echo ($countLoggedInOnlineUsers==1) ? $language->regUser : $language->regUsers;?>
 / <?php echo $countAnonymousOnlineUsers; ?>
 <?php echo ($countAnonymousOnlineUsers==1)? $language->guestUser : $language->guestUsers;?> )
<br/>
<?php echo implode(', ', $activeUsers); ?>