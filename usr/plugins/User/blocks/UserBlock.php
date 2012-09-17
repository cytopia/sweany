<?php
class UserBlock extends BlockController
{
	protected $plugin			= 'User';

	/* ***************************************** FORM VALIDATOR ******************************************/
	protected $formValidator = array(
		// Login Form
		'block_form_login'	=> array(
			'username|password' => array(
				'rule-1'		=> array(
					'rule'			=> array('userCheckLogin'),
					'error'			=> '',
					'err_field'		=> 'username',
				),
				'rule-2'		=> array(
					'rule'			=> array('userIsNotLocked'),
					'error'			=> '',
					'err_field'		=> 'username',
				),
				'rule-3'		=> array(
					'rule'			=> array('userIsNotDeleted'),
					'error'			=> '',
					'err_field'		=> 'username',
				),
				'rule-4'		=> array(
					'rule'			=> array('userIsEnabled'),
					'error'			=> '',
					'err_field'		=> 'username',
				),
			),
			'username'		=> array(
				'rule-1'		=> array(
					'rule'			=> array('minLen', 3),
					'error'			=> '',
				),
			),
		),

		// Register Form
		'block_form_register'	=> array(
			'username'		=> array(
				'rule-1'		=> array(
					'rule'			=> array('userNameAvailable'),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('maxLen', 12),
					'error'			=> '',
				),
				'rule-3'		=> array(
					'rule'			=> array('isAlphaNumeric'),
					'error'			=> '',
				),
				'rule-4'		=> array(
					'rule'			=> array('minLen', 3),
					'error'			=> '',
				),
			),
			'email'			=> array(
				'rule-1'		=> array(
					'rule'			=> array('isEmail'),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('userEmailAvailable'),
					'error'			=> '',
				),
			),
			'password'		=> array(
				'rule-1'		=> array(
					'rule'			=> array('minLen', 5),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('maxLen', 30),
					'error'			=> '',
				),
			),
		),

		// Lost Passowrd Form
		'block_form_lost_password'	=> array(
			'email'		=> array(
				'rule-1'		=> array(
					'rule'			=> array('userEmailNotExist'),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('minLen', 3),
					'error'			=> '',
				),
				'rule-3'		=> array(
					'rule'			=> array('isEmail'),
					'error'			=> '',
				),
			),
		),

		// Reset Password Form
		'block_reset_password_form'	=> array(
			'password1'			=> array(
				'rule-1'		=> array(
					'rule'			=> array('minLen', 5),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('maxLen', 30),
					'error'			=> '',
				),
			),
			'password1|password2' => array(
				'rule-1'		=> array(
					'rule'			=> array('equals'),
					'error'			=> '',
					'err_field'		=> 'password1',
				),
			),
		),
	);


	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/

	/**
	 * @return integer
	 * 		returns 1 on success	(and does not need to be rendered then)
	 * 		returns 0 on failure	(needs to be rendered)
	 */
	public function loginForm()
	{
		// ----- Return true, if already logged in
		if ( $this->user->isLoggedIn() )
		{
			$this->render = false;
			return 1;
		}

		// -------------------- SET FORM ERROR LANGUAGE ---------------------- //
		$this->formValidator['block_form_login']['username']['rule-1']['error']				= $this->language->loginErrorNameTooShort;
		$this->formValidator['block_form_login']['username|password']['rule-1']['error']	= $this->language->loginErrorWrongPassword;
		$this->formValidator['block_form_login']['username|password']['rule-2']['error']	= $this->language->loginErrorUserIsLocked;
		$this->formValidator['block_form_login']['username|password']['rule-3']['error']	= $this->language->loginErrorUserIsDeleted;
		$this->formValidator['block_form_login']['username|password']['rule-4']['error']	= $this->language->loginErrorUserIsNotEnabled;

		// -------------------- LOGIN ---------------------- //
		if ( $this->validateForm('block_form_login') )
		{
			$username	= Form::getValue('username');
			$password	= Form::getValue('password');

			$this->render = false;
			$this->user->login($username, $password);
			return 1;
		}
		$this->set('language', $this->language);
		$this->view('form_login');
		return 0;
	}

	/**
	 * @param	string	Controller for User Validation
	 * @param	string	Method for User Validation
	 * @return	integer
	 * 		returns user_id on success	(and does not need to be rendered then)
	 * 		returns 0		on failure	(needs to be rendered)
	 */
	public function registerForm($userValidateCtl, $userValidateMethod)
	{
		// -------------------- SET FORM ERROR LANGUAGE ---------------------- //
		$this->formValidator['block_form_register']['username']['rule-1']['error']	= $this->language->regErrorNameExists;
		$this->formValidator['block_form_register']['username']['rule-2']['error']	= $this->language->regErrorNameTooLong;
		$this->formValidator['block_form_register']['username']['rule-3']['error']	= $this->language->regErrorNameOnlyAlphaNum;
		$this->formValidator['block_form_register']['username']['rule-4']['error']	= $this->language->regErrorNameTooShort;
		$this->formValidator['block_form_register']['email']['rule-1']['error']		= $this->language->regErrorEmailInvalid;
		$this->formValidator['block_form_register']['email']['rule-2']['error']		= $this->language->regErrorEmailExists;
		$this->formValidator['block_form_register']['password']['rule-1']['error']	= $this->language->regErrorPasswordTooShort;
		$this->formValidator['block_form_register']['password']['rule-2']['error']	= $this->language->regErrorPasswordTooLong;

		// If Enabled, also validate to accept terms
		if ( Config::get('acceptTermsOnRegister', 'user') )
		{
			$this->formValidator['block_form_register']['terms']['rule-1']['rule']		= array('equals', 1);
			$this->formValidator['block_form_register']['terms']['rule-1']['error']		= $this->language->regErrorAcceptTerms;
		}

		if ( $this->validateForm('block_form_register') )
		{
			$username	= Form::getValue('username');
			$email		= Form::getValue('email');
			$password	= Form::getValue('password');

			// Do not render on success
			$this->render = false;

			// Add user to the system
			$user_id		= $this->user->addUser($username, $password, $email);

			// Retrieve validation key
			$data			= $this->user->data($user_id);
			$validate_key	= $data['validation_key'];
			$validate_url	= 'http://'.$_SERVER['HTTP_HOST'].DS.$userValidateCtl.DS.$userValidateMethod.DS.$validate_key;
			$validate_link	= '<a href="'.$validate_url.'">'.$validate_url.'</a>';

			// Write Email to the user (contains validation link)
			$subject		= $this->language->registerEmailSubject;
			$body			= sprintf($this->language->registerEmailBody, $username, $validate_link);

			Mailer::sendHtml($email, $subject, $body);

			return $user_id;
		}
		$this->set('language', $this->language);
		$this->view('form_register');
		return 0;
	}


	public function lostPasswordForm($userResetPasswordCtl, $userResetPasswordMethod)
	{
		// -------------------- SET FORM ERROR LANGUAGE ---------------------- //
		$this->formValidator['block_form_lost_password']['email']['rule-1']['error']	= $this->language->errorEmailNotExist;
		$this->formValidator['block_form_lost_password']['email']['rule-2']['error']	= $this->language->errorEmailTooShort;
		$this->formValidator['block_form_lost_password']['email']['rule-3']['error']	= $this->language->errorInvalidEmail;

		if ( $this->validateForm('block_form_lost_password') )
		{
			$email		= Form::getValue('email');

			// Do not render on success
			$this->render = false;

			// Get Password reset key
			$user_id	= $this->user->getIdByEmail($email);
			$reset_key	= $this->user->setResetPasswordKey($user_id);
			$reset_url	= 'http://'.$_SERVER['HTTP_HOST'].DS.$userResetPasswordCtl.DS.$userResetPasswordMethod.DS.$reset_key;
			$reset_link	= '<a href="'.$reset_url.'">'.$reset_url.'</a>';

			// Write email to user containing the password reset key
			$subject	= $this->language->resetPasswordEmailSubject;
			$body		= sprintf($this->language->resetPasswordEmailBody, $this->user->name($user_id), $reset_link);

			Mailer::sendHtml($email, $subject, $body);

			return $user_id;
		}
		$this->set('language', $this->language);
		$this->view('form_lost_password');
		return 0;
	}




	public function resetPasswordForm($password_reset_key)
	{
		$this->formValidator['block_reset_password_form']['password1']['rule-1']['error']			= $this->language->errorPasswordTooShort;
		$this->formValidator['block_reset_password_form']['password1']['rule-2']['error']			= $this->language->errorPasswordTooLong;
		$this->formValidator['block_reset_password_form']['password1|password2']['rule-1']['error']	= $this->language->errorPasswordsDoNotMatch;

		if ( $this->validateForm('block_reset_password_form') )
		{
			$password1	= Form::getValue('password1');
			$password2	= Form::getValue('password2');

			// Do not render on success
			$this->render = false;

			$user_id = $this->user->getIdByResetPasswordKey($password_reset_key);

			// remove reset_password_key
			$this->user->removeResetPasswordKey($user_id);

			// change password
			$this->user->updatePassword($password1, $user_id);

			return 1;
		}
		$this->set('language', $this->language);
		$this->view('form_reset_password');
		return 0;
	}



	public function loginLink($loginCtl, $loginMethod, $signupCtl, $signupMethod)
	{
		// VIEW VARIABLES
		$this->set('loginCtl', $loginCtl);
		$this->set('loginMethod', $loginMethod);
		$this->set('signupCtl', $signupCtl);
		$this->set('signupMethod', $signupMethod);

		$this->set('language', $this->language);

		// VIEW OPTIONS
		$this->view('login_link');
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
		$this->view('logout_link');
	}


	public function latestUsersList($total)
	{
		$this->set('users', $this->user->getLatestUsers($total));
		$this->view('latest_users_list');
	}
}