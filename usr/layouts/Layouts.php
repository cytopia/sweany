<?php

class Layouts extends LayoutController
{
	public function FrontPage()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			$params	= array('User', 'login', 'User', 'login');
			$this->attachPluginBlock('bLoginLogoutBox', 'User', 'User', 'loginLink', $params);
		}
		else
		{
			$params	= array('User', 'logout');
			$this->attachPluginBlock('bLoginLogoutBox', 'User', 'User', 'logoutLink', $params);
		}

		$this->view('frontpage');
	}

	public function DefaultLayout()
	{
		$this->view('default');
	}
}