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
			$bLoginBox	= Blocks::get('User', 'User', 'loginLink', $params);
			$this->set('bLoginBox', $bLoginBox['html']);
		}
		else
		{
			$params		= array('User', 'logout');
			$bLogoutBox	=  Blocks::get('User', 'User', 'logoutLink', $params);
			$this->set('bLogoutBox', $bLogoutBox['html']);
		}
		$this->view('frontpage');
	}
}