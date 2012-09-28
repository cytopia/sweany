<?php
class User extends PageController
{
	// This is a plugin
	protected $plugin = 'User';

	// Dont need a model
	protected $hasModel = false;


	/**
	 * Login
	 */
	public function login()
	{
		// get the LoginForm
		// Its return value holds the successful login
		$loggedIn = $this->attachBlock('bLoginForm', 'User', 'User', 'loginForm');

		// User is already logged in or has logged in successfully
		if ( $loggedIn )
		{
			// Where did the user come from
			$referrer = Session::get('referrer');

			// Redirect where he came from
			if ( isset($referrer['controller']) && isset($referrer['method']) )
			{
				$params = isset($referrer['params']) ? $referrer['params'] : array();
				Session::del('referrer');
				$this->redirect($referrer['controller'], $referrer['method'], $params);
				return;
			}
			// Redirect to home page
			else
			{
				$this->redirectHome();
				return;
			}
		}
		// get the registerForm
		// Its return value holds the user_if on successful registration or 0
		$user_id = $this->attachBlock('bRegisterForm', 'User', 'User', 'registerForm', array(__CLASS__, 'validate'));

		if ( $user_id > 0 )
		{
			// If registration is complete, redirect him
			$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->language->registerCompleteHead, $this->language->registerCompleteBody, 20);
			return;
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->language->pageTitle);

		$this->view('login');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	/**
	 * Logout
	 * @param	string	$session_id
	 */
	public function logout($session_id = null)
	{
		if ( $this->user->logout($session_id) )
		{
			$this->redirectDelayedHome($this->language->loggedOutHead, $this->language->loggedOutBody, 10);
			return;
		}
		else
		{
			$this->redirectHome();
			return;
		}
	}



	/**
	 * Lost Password
	 * Request new password
	 */
	public function lostPassword()
	{
		$submitted = $this->attachBlock('bLostPasswordForm', 'User', 'User', 'lostPasswordForm', array(__CLASS__, 'resetPassword'));

		// User has successfully submitted the form
		if ( $submitted > 0 )
		{
			$this->redirectDelayed(__CLASS__, 'login', null, $this->language->passwordReset, $this->language->passwordResetText, 20);
			return;
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->language->pageTitle);

		$this->view('lost_password');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	/**
	 * Validate the registered account
	 * The validation key will be sent to the user by email
	 *
	 * @param unknown_type $validation_key
	 */
	public function validate($validation_key = null)
	{
		$success = ($this->user->validate($validation_key)) ? true : false;

		$this->set('success', $success);
		$this->view('validate_account');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}



	/**
	 * Reset Password via password reset key
	 * @param string $reset_password_key
	 */
	public function resetPassword($password_reset_key = null)
	{
		if ( $this->user->checkPasswordResetKey($password_reset_key) )
		{
			$bResetPasswordForm = Blocks::get('User', 'User', 'resetPasswordForm', array($password_reset_key));

			// User has successfully submitted the form
			if ( $bResetPasswordForm['ret'] > 0 )
			{
				// redirect to info page
				$this->redirectDelayed(__CLASS__, 'login', null, $this->language->passwordChanged, $this->language->passwordChangedText, 20);
				return;
			}
			$this->set('bResetPasswordForm', $bResetPasswordForm['html']);
			$this->view('reset_password_valid');
		}
		else
		{
			$this->view('reset_password_invalid');
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->language->pageTitle);
		$this->set('language', $this->language);

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}
