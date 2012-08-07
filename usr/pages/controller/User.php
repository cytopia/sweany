<?php
class User extends PageController
{
	// Define the forms with their respecting fields, validation function
	// and error messages
	// TODO: The language Module will take care about the error messages later
	// right now it is hardcoded, but can be overriden in each function (below)
	// using this form

	// structure
	// [formName][fieldName][uniqueDescriptionField]['callback OR rule']
	// [formName][fieldName][uniqueDescriptionField]['error']
	//
	// callback is a custom function defined in the model of this controller
	// rule is a function defined in sweany/lib/Rules.php
	//
	// callback can thus access database and validate against it
	//
	// rule function only do operation on variables (check Rules.php for details)
	//
	//
	// additionally every function in the model the the nameconvention
	// $form_name.'Validate()' will be called to do custom validation
	// This can be used to validate fields against database entries
	//
	protected $formValidator = array(
		'form_login'	=> array(
			'username'=> array(
				'custom'		=> array(
					'callback'		=> 'checkLogin',
					'error'			=> 'Falscher Benutzername oder Passwort',
				),
				'minLen'		=> array(
					'rule'			=> array('minLen', 3),
					'error'			=> 'Benutzername zu kurz',
				),
			),
		),
		'form_register'	=> array(
			'username'		=> array(
				'exists'		=> array(
					'callback'		=> 'usernameExists',
					'error'			=> 'Benutzername existiert bereits',
				),
				'maxLen'		=> array(
					'rule'			=> array('maxLen', 12),
					'error'			=> 'maximal 12 Zeichen',
				),
				'alphaNum'		=> array(
					'rule'			=> array('isAlphaNumeric'),
					'error'			=> 'nur aus alphanumerischen Zeichen',
				),
				'minLen'		=> array(
					'rule'			=> array('minLen', 3),
					'error'			=> 'mind. 3 Zeichen',
				),
			),
			'email'			=> array(
				'isEmail'		=> array(
					'rule'			=> array('isEmail'),
					'error'			=> 'keine g&uuml;ltige Email',
				),
				'custom'		=> array(
					'callback'		=> 'emailExists',
					'error'			=> 'Emailadresse existiert bereits',
				),
			),
			'password'		=> array(
				'minLen'		=> array(
					'rule'			=> array('minLen', 5),
					'error'			=> 'mind. 5 Zeichen',
				),
				'maxLen'		=> array(
					'rule'			=> array('maxLen', 30),
					'error'			=> 'max 30 Zeichen',
				),
			),
			'agb'			=> array(
				'accept'		=> array(
					'rule'			=> array('equals',1),
					'error'			=> 'Um der Seite beizutreten, musst der Datenschutzerkl&auml;rung und den Nutzungsbedinungen zustimmen.',
				),
			),
		),
	);


	public function login()
	{
		// Where did the user come from
		$referrer = Session::get('referrer');

		// If user is already logged in redirect him
		if ( $this->user->isLoggedIn() )
		{
			// Redirect where he came from
			if ( isset($referrer['controller']) && isset($referrer['method']) )
			{
				$params = isset($referrer['params']) ? $referrer['params'] : array();
				Session::del('referrer');
				$this->redirect($referrer['controller'], $referrer['method'], $params);
				return;
			}
			// Redirect to the home page
			else
			{
				$this->redirectHome();
				return;
			}
		}


		// -------------------- LOGIN ---------------------- //
		if ( $this->validateForm('form_login')  )
		{
			$username	= Form::getValue('username');
			$password	= Form::getValue('password');

			$this->user->login($username, $password);

			if ( isset($referrer['controller']) && isset($referrer['method']) )
			{
				$params = isset($referrer['params']) ? $referrer['params'] : array();
				Session::del('referrer');
				$this->redirect($referrer['controller'], $referrer['method'], $params);
				return;
			}
			else
			{
				$this->redirectHome();
				return;
			}
		}
		// set custom error messages
		else
		{
			$username	= Form::getValue('username');
			$password	= Form::getValue('password');
			$user_id	= $this->user->getIdByNameAndPassword($username, $password);

			if ( $user_id > 0 )
			{
				if ( $this->user->isLocked($user_id) )
					Form::setError('username', $this->language->accountLocked);
				else if ( $this->user->isDeleted($user_id) )
					Form::setError('username', $this->language->accountDeleted);
				else if ( !$this->user->isEnabled($user_id) )
					Form::setError('username', $this->language->accountDisabled);
			}
		}

		// -------------------- REGISTER ---------------------- //
		if ( $this->validateForm('form_register')  )
		{
			$username	= Form::getValue('username');
			$email		= Form::getValue('email');
			$password	= Form::getValue('password');


			// Add user to the system
			$user_id	= $this->user->addUser($username, $password, $email);


			// Write Email to the user (contains validation link)
			// TODO: still using core Class here, need to put functionality into the User-Helper
			$user_data		= \Core\Init\CoreMySql::fetchRowById('users', $user_id, array());
			$validate_url	= 'http://'.$_SERVER['HTTP_HOST'].DS.__CLASS__.DS.'validate'.DS.$user_data['validation_key'];
			$validate_link	= '<a href="'.$validate_url.'">'.$validate_url.'</a>';

			$subject	= $this->language->getCustom('/root/custom/MailSection/mail[@id="welcome"]', 'subject');
			$message	= $this->language->getCustom('/root/custom/MailSection/mail[@id="welcome"]', 'body');
			$message	= sprintf($message, $username, $validate_link);
			Mailer::sendHtml($email, $subject, $message);

			$this->redirectDelayed(__CLASS__, __FUNCTION__, null, $this->language->registerCompleteTitle, $this->language->registerCompleteBody, 20);
			return;
		}
		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->language->title);

		$this->view('login.tpl.php');
	}

	public function logout($session_id = null)
	{
		$this->render = false;

		if ( $this->user->logout($session_id) )
		{
			$this->redirectDelayedHome($this->language->title, $this->language->note, 5);
			return;
		}
		else
		{
			$this->redirectHome();
			return;
		}
	}

	public function validate($validation_key = null)
	{
		$success = $this->user->validate($validation_key) ? true : false;

		$this->set('language', $this->language);
		$this->set('success', $success);
		$this->view('validate.tpl.php');
	}
}
?>