<?php

class Layouts extends LayoutController
{
	public function FrontPage()
	{
		$this->set('language', $this->language);
		$this->set('user', $this->user);

		if ( !$this->user->isLoggedIn() )
		{
			$params		= array('User', 'login', 'User', 'login');
			$this->attachBlock('bLoginBox', 'User', 'User', 'loginLink', $params);
		}
		else
		{
			$params		= array('User', 'logout');
			$this->attachBlock('bLogoutBox', 'User', 'User', 'logoutLink', $params);
		}
		$this->view('frontpage');
	}
}