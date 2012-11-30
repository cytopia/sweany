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
					'rule'			=> array('maxLen', 12),	// Overriden in registerForm
					'error'			=> '',
				),
				'rule-3'		=> array(
					'rule'			=> array('isAlphaNumeric'),
					'error'			=> '',
				),
				'rule-4'		=> array(
					'rule'			=> array('minLen', 3),	// Overriden in registerForm
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
					'rule'			=> array('minLen', 5),	// Overriden in registerForm
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('maxLen', 30),	// Overriden in registerForm
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
					'rule'			=> array('minLen', 5),	// Overriden in resetPasswordForm
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('maxLen', 30),	// Overriden in resetPasswordForm
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
		if ( $this->core->user->isLoggedIn() )
		{
			$this->render = false;
			return 1;
		}

		// -------------------- SET FORM ERROR LANGUAGE ---------------------- //
		$this->formValidator['block_form_login']['username']['rule-1']['error']				= $this->core->language->loginErrorNameTooShort;
		$this->formValidator['block_form_login']['username|password']['rule-1']['error']	= $this->core->language->loginErrorWrongPassword;
		$this->formValidator['block_form_login']['username|password']['rule-2']['error']	= $this->core->language->loginErrorUserIsLocked;
		$this->formValidator['block_form_login']['username|password']['rule-3']['error']	= $this->core->language->loginErrorUserIsDeleted;
		$this->formValidator['block_form_login']['username|password']['rule-4']['error']	= $this->core->language->loginErrorUserIsNotEnabled;

		// -------------------- LOGIN ---------------------- //
		if ( $this->validateForm('block_form_login') )
		{
			$username	= Form::getValue('username');
			$password	= Form::getValue('password');

			$this->render = false;
			$this->core->user->login($username, $password);
			return 1;
		}
		$this->set('language', $this->core->language);
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
		$disableRegistration	= Config::get('disableRegistration', 'user');

		// Allow registration
		if ( !$disableRegistration )
		{

			// -------------------- GET CONFIG DEFINES ---------------------- //
			$userNameMinLen = Config::get('userNameMinLen', 'user');
			$userNameMaxLen = Config::get('userNameMaxLen', 'user');
			$passwordMinLen = Config::get('passwordMinLen', 'user');
			$passwordMaxLen = Config::get('passwordMaxLen', 'user');

			// -------------------- OVERRIDE FORM ERROR DEFINES ---------------------- //
			$this->formValidator['block_form_register']['username']['rule-2']['rule'] = array('maxLen', $userNameMaxLen);
			$this->formValidator['block_form_register']['username']['rule-4']['rule'] = array('minLen', $userNameMinLen);
			$this->formValidator['block_form_register']['password']['rule-1']['rule'] = array('minLen', $passwordMinLen);
			$this->formValidator['block_form_register']['password']['rule-2']['rule'] = array('maxLen', $passwordMaxLen);

			// -------------------- OVERRIDE FORM ERROR LANGUAGE ---------------------- //
			$this->formValidator['block_form_register']['username']['rule-1']['error']	= $this->core->language->regErrorNameExists;
			$this->formValidator['block_form_register']['username']['rule-2']['error']	= sprintf($this->core->language->regErrorNameTooLong, $userNameMaxLen);
			$this->formValidator['block_form_register']['username']['rule-3']['error']	= $this->core->language->regErrorNameOnlyAlphaNum;
			$this->formValidator['block_form_register']['username']['rule-4']['error']	= sprintf($this->core->language->regErrorNameTooShort, $userNameMinLen);
			$this->formValidator['block_form_register']['email']['rule-1']['error']		= $this->core->language->regErrorEmailInvalid;
			$this->formValidator['block_form_register']['email']['rule-2']['error']		= $this->core->language->regErrorEmailExists;
			$this->formValidator['block_form_register']['password']['rule-1']['error']	= sprintf($this->core->language->regErrorPasswordTooShort, $passwordMinLen);
			$this->formValidator['block_form_register']['password']['rule-2']['error']	= sprintf($this->core->language->regErrorPasswordTooLong, $passwordMaxLen);

			// If Enabled, also validate to accept terms
			if ( Config::get('acceptTermsOnRegister', 'user') )
			{
				$this->formValidator['block_form_register']['terms']['rule-1']['rule']		= array('equals', 1);
				$this->formValidator['block_form_register']['terms']['rule-1']['error']		= $this->core->language->regErrorAcceptTerms;
			}

			if ( $this->validateForm('block_form_register') )
			{
				$username	= Form::getValue('username');
				$email		= Form::getValue('email');
				$password	= Form::getValue('password');

				// Do not render on success
				$this->render = false;

				// Add user to the system
				$user_id		= $this->core->user->addUser($username, $password, $email);

				// Retrieve validation key
				$data			= $this->core->user->data($user_id);
				$validate_key	= $data->validation_key;
				$validate_url	= Url::getSiteUrl().'/'.$userValidateCtl.'/'.$userValidateMethod.'/'.$validate_key;
				$validate_link	= '<a href="'.$validate_url.'">'.$validate_url.'</a>';

				// Write Email to the user (contains validation link)
				$subject		= $this->core->language->registerEmailSubject;
				$body			= sprintf($this->core->language->registerEmailBody, $username, $validate_link);

				Mailer::sendHtml($email, $subject, $body);

				return $user_id;
			}
		}
		// Set status of being allowed to register or now
		$this->set('disabled', $disableRegistration);

		$this->set('language', $this->core->language);
		$this->view('form_register');
		return 0;
	}


	public function lostPasswordForm($userResetPasswordCtl, $userResetPasswordMethod)
	{
		// -------------------- SET FORM ERROR LANGUAGE ---------------------- //
		$this->formValidator['block_form_lost_password']['email']['rule-1']['error']	= $this->core->language->errorEmailNotExist;
		$this->formValidator['block_form_lost_password']['email']['rule-2']['error']	= $this->core->language->errorEmailTooShort;
		$this->formValidator['block_form_lost_password']['email']['rule-3']['error']	= $this->core->language->errorInvalidEmail;

		if ( $this->validateForm('block_form_lost_password') )
		{
			$email		= Form::getValue('email');

			// Do not render on success
			$this->render = false;

			// Get Password reset key
			$user_id	= $this->core->user->getIdByEmail($email);
			$reset_key	= $this->core->user->setResetPasswordKey($user_id);
			$reset_url	= Url::getSiteUrl().'/'.$userResetPasswordCtl.'/'.$userResetPasswordMethod.'/'.$reset_key;
			$reset_link	= '<a href="'.$reset_url.'">'.$reset_url.'</a>';

			// Write email to user containing the password reset key
			$subject	= $this->core->language->resetPasswordEmailSubject;
			$body		= sprintf($this->core->language->resetPasswordEmailBody, $this->core->user->name($user_id), $reset_link);

			Mailer::sendHtml($email, $subject, $body);

			return $user_id;
		}
		$this->set('language', $this->core->language);
		$this->view('form_lost_password');
		return 0;
	}




	public function resetPasswordForm($password_reset_key)
	{
		// -------------------- GET CONFIG DEFINES ---------------------- //
		$passwordMinLen = Config::get('passwordMinLen', 'user');
		$passwordMaxLen = Config::get('passwordMaxLen', 'user');

		// -------------------- OVERRIDE FORM ERROR DEFINES ---------------------- //
		$this->formValidator['block_form_lost_password']['password1']['rule-1']['rule'] = array('maxLen', $passwordMinLen);
		$this->formValidator['block_form_lost_password']['password1']['rule-2']['rule'] = array('minLen', $passwordMaxLen);

		$this->formValidator['block_reset_password_form']['password1']['rule-1']['error']			= $this->core->language->errorPasswordTooShort;
		$this->formValidator['block_reset_password_form']['password1']['rule-2']['error']			= $this->core->language->errorPasswordTooLong;
		$this->formValidator['block_reset_password_form']['password1|password2']['rule-1']['error']	= $this->core->language->errorPasswordsDoNotMatch;

		if ( $this->validateForm('block_reset_password_form') )
		{
			$password1	= Form::getValue('password1');
			$password2	= Form::getValue('password2');

			// Do not render on success
			$this->render = false;

			$user_id = $this->core->user->getIdByResetPasswordKey($password_reset_key);

			// remove reset_password_key
			$this->core->user->removeResetPasswordKey($user_id);

			// change password
			$this->core->user->updatePassword($password1, $user_id);

			return 1;
		}
		$this->set('language', $this->core->language);
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

		$this->set('language', $this->core->language);

		// VIEW OPTIONS
		$this->view('login_link');
	}


	public function logoutLink($logoutCtl, $logoutMethod)
	{
		// VIEW VARIABLES
		$this->set('logoutCtl', $logoutCtl);
		$this->set('logoutMethod', $logoutMethod);
		$this->set('userName', $this->core->user->name());
		$this->set('sessionId', \Sweany\Session::id());

		$this->set('language', $this->core->language);

		// VIEW OPTIONS
		$this->view('logout_link');
	}


	public function latestUsersList($total)
	{
		$this->set('users', $this->core->user->getLatestUsers($total));
		$this->view('latest_users_list');
	}
}