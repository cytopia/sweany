<?php

class Layouts extends LayoutController
{
	public function FrontPage()
	{
		if ( !$this->user->isLoggedIn() )
		{
			$params = array('User', 'login', 'User', 'login');
			$this->attachBlock('loginBox', 'Login', 'view_loginLinkBox', $params);
		}
		else
		{
			$this->attachBlock('unreadMessages', 'Messages', 'view_getUnreadMessage', array($this->user->id()));
		}
//		$this->attachBlock();
//		$this->set();
		$this->view('frontpage.tpl.php');
	}
}