<?php
class UserBlock extends BlockController
{
	public $helpers = array('Html', 'Form');


	/* ***************************************** FORM VALIDATOR ******************************************/
	protected $formValidator = array();


	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/
	public function loginLink($loginCtl, $loginMethod, $signupCtl, $signupMethod)
	{
		// VIEW VARIABLES
		$this->set('loginCtl', $loginCtl);
		$this->set('loginMethod', $loginMethod);
		$this->set('signupCtl', $signupCtl);
		$this->set('signupMethod', $signupMethod);

		$this->set('language', $this->language);

		// VIEW OPTIONS
		$this->view('login_link.tpl.php');
	}

	public function logoutLink($logoutCtl, $logoutMethod)
	{
		// VIEW VARIABLES
		$this->set('logoutCtl', $logoutCtl);
		$this->set('logoutMethod', $logoutMethod);
		$this->set('userName', $this->user->name());
		$this->set('sessionId', Session::getId());

		$this->set('language', $this->language);

		// VIEW OPTIONS
		$this->view('logout_link.tpl.php');
	}

}
?>