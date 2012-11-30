<?php
class User extends PageController
{
	// This is a plugin
	protected $plugin = 'User';

	// Dont need a model
	protected $hasModel = false;



	protected $formValidator = array(
		'form_edit_data'	=> array(
			'email1'			=> array(
				'rule-1'		=> array(
					'rule'			=> array('isEmail'),
					'error'			=> '',
				),
				'rule-2'		=> array(
					'rule'			=> array('userOtherUserNotHasThisEmail'),
					'error'			=> '',
				),
			),
			'email1|email2' => array(
				'rule-1'		=> array(
					'rule'			=> array('equals'),
					'error'			=> '',
					'err_field'		=> 'email2',
				),
			),
			'old_password'	=> array(
				'rule-1'		=> array(
					'rule'			=> array('userIsMyPassword'),
					'error'			=> '',
				),
			),
			'new_password1' => array(
				'rule-1'		=> array(
					'rule'			=> array('minLenIfNotEmpty',5),
					'error'			=> '',
				),
			),
			'new_password1|new_password2' => array(
				'rule-1'		=> array(
					'rule'			=> array('equals'),
					'error'			=> '',
					'err_field'		=> 'new_password2',
				),
			),
		),
	);





	/* **********************************************************************************************************************
	*
	*   S E T T I N G S
	*
	* **********************************************************************************************************************/

	private $userWriteMessageLinkEnable	= false;
	private $userWriteMessageIconEnable	= false;
	private $userWriteMessageIconPath;
	private $userWriteMessageCtl;
	private $userWriteMessageMethod;

	public function __construct()
	{
		parent::__construct();

		// Controller Defines needed to build <href> links in the views
		$this->userWriteMessageLinkEnable	= Config::get('userWriteMessageLinkEnable', 'user');
		$this->userWriteMessageIconEnable	= Config::get('userWriteMessageIconEnable', 'user');
		$this->userWriteMessageIconPath		= Config::get('userWriteMessageIconPath', 'user');
		$this->userWriteMessageCtl			= Config::get('userWriteMessageCtl', 'user');
		$this->userWriteMessageMethod		= Config::get('userWriteMessageMethod', 'user');
	}


	/* ********************************************  L O G I N / R E G I S T E R   F U N C T I O N S  ******************************************** */


	/**
	 * Login
	 */
	public function login()
	{
		// get the LoginForm
		// Its return value holds the successful login
		$loggedIn = $this->attachPluginBlock('bLoginForm', 'User', 'User', 'loginForm');

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
			}
			// Redirect to home page
			else
			{
				$this->redirectHome();
			}
		}
		// get the registerForm
		// Its return value holds the user_if on successful registration or 0
		$user_id = $this->attachPluginBlock('bRegisterForm', 'User', 'User', 'registerForm', array(__CLASS__, 'validate'));

		if ( $user_id > 0 )
		{
			// If registration is complete, redirect him
			$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->core->language->registerCompleteHead, $this->core->language->registerCompleteBody, 20);
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		$this->view('login');

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

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
		if ( $this->core->user->logout($session_id) )
		{
			$this->redirectDelayedHome($this->core->language->loggedOutHead, $this->core->language->loggedOutBody, 10);
		}
		else
		{
			$this->redirectHome();
		}
	}



	/**
	 * Lost Password
	 * Request new password
	 */
	public function lostPassword()
	{
		$submitted = $this->attachPluginBlock('bLostPasswordForm', 'User', 'User', 'lostPasswordForm', array(__CLASS__, 'resetPassword'));

		// User has successfully submitted the form
		if ( $submitted > 0 )
		{
			$this->redirectDelayed(__CLASS__, 'login', null, $this->core->language->passwordReset, $this->core->language->passwordResetText, 20);
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		$this->view('lost_password');

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

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
		$success = ($this->core->user->validate($validation_key)) ? true : false;

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		$this->set('success', $success);
		$this->set('language', $this->core->language);

		$this->view('validate_account');

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

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
		if ( $this->core->user->checkPasswordResetKey($password_reset_key) )
		{
			$success = $this->attachPluginBlock('bResetPasswordForm', 'User', 'User', 'resetPasswordForm', array($password_reset_key));

			// User has successfully submitted the form
			if ( $success > 0 )
			{
				// redirect to info page
				$this->redirectDelayed(__CLASS__, 'login', null, $this->core->language->passwordChanged, $this->core->language->passwordChangedText, 20);
			}
			$this->view('reset_password_valid');
		}
		else
		{
			$this->view('reset_password_invalid');
		}

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('language', $this->core->language);

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}




	/* ********************************************  S H O W   F U N C T I O N S  ******************************************** */



	public function show($user_id = null)
	{
		$exists = $this->core->user->exists($user_id);	// Does the user exist?
		$is_me	= ($user_id == $this->core->user->id());	// Is it my own profile?


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('userWriteMessageLinkEnable', $this->userWriteMessageLinkEnable);
		$this->set('userWriteMessageIconEnable', $this->userWriteMessageIconEnable);
		$this->set('userWriteMessageIconPath', $this->userWriteMessageIconPath);
		$this->set('userWriteMessageCtl', $this->userWriteMessageCtl);
		$this->set('userWriteMessageMethod', $this->userWriteMessageMethod);

		$this->set('language', $this->core->language);
		$this->set('exists', $exists);
		$this->set('is_me', $is_me);
		$this->set('user_id', $user_id);
		$this->set('user_name', $this->core->user->name($user_id));

		// SET VIEW
		$this->view('show_profile');

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}




	/* ********************************************  E D I T   F U N C T I O N S  ******************************************** */

	public function settings()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect(__CLASS__, 'login');
		}

		$timezones	= DateTimeZone::listIdentifiers();
		$timezones	= array_combine($timezones, $timezones);
		$def_zone	= $this->core->user->timezone();

		$languages	= $GLOBALS['LANGUAGE_AVAILABLE'];
		$def_lang	= $this->core->user->language();

		$this->set('timezones', $timezones);
		$this->set('def_zone', $def_zone);
		$this->set('languages', $languages);
		$this->set('def_lang', $def_lang);
		$this->set('language', $this->core->language);

		if ( Form::isSubmitted('form_edit_settings') )
		{
			$new_zone	= Form::getValue('timezones');
			$new_lang	= Form::getValue('languages');

			if ( !in_array($new_zone, $timezones) )
			{
				Form::setError('timezones', $this->core->language->errTimezone);
			}
			if ( !in_array($new_lang, array_keys($languages)) )
			{
				Form::setError('languages', $this->core->language->errLanguage);

			}

			if ( Form::isValid('form_edit_settings') )
			{
				$data['timezone']	= $new_zone;
				$data['language']	= $new_lang;

				// User and Language Session are being updated automatically by
				// the user update function
				$this->core->user->update($data);

				$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->core->language->regionalSettingsChanged, $this->core->language->regionalSettingsChangedText, 5);
			}
		}


		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		$this->view('settings');

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

		// LAYOUT OPTIONS
		if  ( Config::exists('editFormLayout', 'user') )
		{
			$layout = Config::get('editFormLayout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}

	public function editData()
	{
		if ( !$this->core->user->isLoggedIn() )
		{
			Session::set('referrer', array('controller'	=> __CLASS__, 'method' => __FUNCTION__));
			$this->redirect(__CLASS__, 'login');
		}

		$this->formValidator['form_edit_data']['email1']['rule-1']['error']						= $this->core->language->errInvalidEmail;
		$this->formValidator['form_edit_data']['email1']['rule-2']['error']						= $this->core->language->errEmailAlreadyUsed;
		$this->formValidator['form_edit_data']['email1|email2']['rule-1']['error']				= $this->core->language->errEmailRepeatWrong;
		$this->formValidator['form_edit_data']['old_password']['rule-1']['error']				= $this->core->language->errInvalidPassword;
		$this->formValidator['form_edit_data']['new_password1']['rule-1']['error']				= $this->core->language->errPasswordTooShort;
		$this->formValidator['form_edit_data']['new_password1|new_password2']['rule-1']['error']= $this->core->language->errPasswordRepeatWrong;

		if ( $this->validateForm('form_edit_data') )
		{
			$email		= Form::getValue('email1');
			$password	= Form::getValue('new_password1');

			if ( $password )
			{
				$this->core->user->updatePassword($password);
				$this->core->user->update(array('email' => $email));
				$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->core->language->emailAndPasswordChanged, $this->core->language->emailAndPasswordChangedText, 5);
			}
			else
			{
				$this->core->user->update(array('email' => $email));
				$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->core->language->passwordChanged, $this->core->language->passwordChangedText, 5);
			}
		}

		$data = $this->core->user->data();

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('email', $data->email);
		$this->set('language', $this->core->language);

		// CSS OPTIONS
		if ( Config::exists('userCssEnable', 'user') && Config::exists('userCssName', 'user') && Config::get('userCssEnable', 'user') )
		{
			Css::addFile('/plugins/User/css/'.Config::get('userCssName', 'user'));
		}
		if ( Config::exists('customCssEnable', 'user') && Config::exists('customCssName', 'user') && Config::get('customCssEnable', 'user') )
		{
			Css::addFile('/css/'.Config::get('customCssName', 'user'));
		}

		$this->view('edit_data');

		// LAYOUT OPTIONS
		if  ( Config::exists('editFormLayout', 'user') )
		{
			$layout = Config::get('editFormLayout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}

}
