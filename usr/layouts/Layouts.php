<?php

class Layouts extends LayoutController
{
	public function FrontPage()
	{
		$this->set('language', $this->language);
		$this->set('user', $this->user);

		if ( !$this->user->isLoggedIn() )
		{
			$params = array('User', 'login', 'User', 'login');
			$this->attachBlock('loginBox', null, 'User', 'loginLink', $params);
		}
		else
		{
			$this->attachBlock('logoutBox', null, 'User', 'logoutLink', array('User', 'logout'));
		}
		$this->view('frontpage.tpl.php');
	}
}