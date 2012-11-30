<?php
class MessageModel extends PageModel
{
	protected $tables	= array(
		'Message' => array('UserMessages', 'UserAlerts',)
	);
}