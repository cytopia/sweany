<?php
class User extends PageController
{
	public $helpers = array('Html', 'HtmlTemplate', 'Form');

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
			$username	= $this->form->getValue('username');
			$password	= $this->form->getValue('password');

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
			$username	= $this->form->getValue('username');
			$password	= $this->form->getValue('password');
			$user_id	= $this->user->getIdByNameAndPassword($username, $password);

			if ( $user_id > 0 )
			{
				if ( $this->user->isLocked($user_id) )
					$this->form->setError('username', 'Das Benutzer Konto ist gesperrt.');
				else if ( $this->user->isDeleted($user_id) )
					$this->form->setError('username', 'Das Benutzer Konto wurde gel&ouml;scht.');
				else if ( !$this->user->isEnabled($user_id) )
					$this->form->setError('username', 'Das Benutzer Konto wurde noch nicht freigeschaltet.');
			}
		}

		// -------------------- REGISTER ---------------------- //
		if ( $this->validateForm('form_register')  )
		{
			$username	= $this->form->getValue('username');
			$email		= $this->form->getValue('email');
			$password	= $this->form->getValue('password');


			// Add user to the system
			$user_id	= $this->user->addUser($username, $password, $email);

			// Write Message to the user about his new moment
			$msgModel	= Loader::loadModel('Nachrichten');
			$can_reply	= 0;	// User cannot reply to this message
			$message	= SystemMessages::getWelcomeMessage($username);
			$message_id	= $msgModel->UserMessages->send(0, $user_id, $message['subject'], $message['body'], $can_reply);


			Session::set('registered', true);
			Session::set('data', array('user_id' => $user_id, 'username' => $username, 'email' => $email));
			$this->redirect(NULL, 'registered');
			return;
		}
		// ADD TEMPLATE ELEMENTS
		$this->htmltemplate->setTitle('Augenblick - einloggen oder registrieren');

		$this->set('menu', 'profile');
		$this->view('login.tpl.php');
	}

	public function index()
	{
	
	}


	public function logout()
	{
		$this->user->logout();
		$this->redirectHome();
		return;
	}

	public function validate($validation_key = null)
	{
		$success = $this->user->validate($validation_key) ? true : false;

		$this->set('success', $success);
		$this->view('validate.tpl.php');
	}



	public function registered()
	{
		$registered = Session::get('registered');

		if ( $registered === true )
		{
			$data = Session::get('data');
			// TODO: outcomment again
			//Session::del('registered');
			//Session::del('data');

			$user_data	= MySql::fetchRowById('users', $data['user_id'], array());
			$this->set('user_data', $user_data);
			$this->set('username', $data['username']);
			$this->set('email', $data['email']);
			$this->view('registered.tpl.php');
		}
		else
		{
			$this->redirect(NULL, 'login');
			return;
		}
	}
}
?>