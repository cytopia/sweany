
<div class="userMessagesBlock">
<?php $link = ($numMessages) ? 'Inbox ('.$numMessages.')' : 'Inbox';?>
<?php echo Html::l($link, $GLOBALS['DEFAULT_MESSAGE_INBOX_CTL'], $GLOBALS['DEFAULT_MESSAGE_INBOX_METHOD']);?>
</div>