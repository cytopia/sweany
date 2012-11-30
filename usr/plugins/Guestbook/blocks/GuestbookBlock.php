<?php
class GuestbookBlock extends BlockController
{
	protected $plugin			= 'Guestbook';


	/* ***************************************** FORM VALIDATOR ******************************************/
	protected $formValidator = array(
		'guestbook_unsigned'	=> array(
			'author'	=> array(
				'minLen'	=> array(
					'rule'		=> array('minLen', 3),
					'error'		=> '',
				),
			),
			'text'	=> array(
				'minLen'	=> array(
					'rule'	=> array('minLen', 10),
					'error'	=> '',
				),
			),
			// bot protection (hidden input field)
			'username'	=> array(
				'maxLen'	=> array(
					'rule'	=> array('maxLen', 0),
					'error'	=> '',
				),
			),
		),
		'guestbook_signed'	=> array(
			'text'	=> array(
				'minLen'	=> array(
					'rule'	=> array('minLen', 10),
					'error'	=> '',
				),
			),
		),
	);


	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/
	public function addEntryUnsigned()
	{
		//------ Override Form validator with appropriate language

		$this->formValidator['guestbook_unsigned']['author']['minLen']['error']	= $this->core->language->form_err_author;
		$this->formValidator['guestbook_unsigned']['text']['minLen']['error']	= $this->core->language->form_err_message;
		$this->formValidator['guestbook_unsigned']['username']['maxLen']['error']= $this->core->language->form_err_bot;


		if (Form::isSubmitted('guestbook_unsigned'))
		{
			$avatar		= Form::getValue('avatar');
			$captcha	= Form::getValue('captcha');

			if ( !$this->avatarExists($avatar) )
			{
				Form::setError('avatar', $this->core->language->form_err_avatar);
			}

			if ( Captcha::read() != $captcha )
			{
				Form::setError('captcha', $this->core->language->form_err_captcha);
			}

			// ------------------------- FORM SUBMITTED AND VALID -------------------------
			if ( $this->validateForm('guestbook_unsigned') && Form::isValid('guestbook_unsigned') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$tblGB		= Loader::loadPluginTable('Guestbook', 'Guestbook');
				$author		= Form::getValue('author');
				$email		= Form::getValue('email');
				$text		= Form::getValue('text');

				$data['author']		= $author;
				$data['email']		= $email;
				$data['avatar']		= $avatar;
				$data['message']	= $text;

				$this->render = false;
				return $tblGB->save($data);	// Return Last insert ID
			}
		}


		// VIEW VARIABLES
		$this->set('language', $this->core->language);


		// VIEW OPTIONS
		$this->view('unsigned');
		return 0;
	}



	public function addEntrySigned()
	{
		//------ Override Form validator with appropriate language
		$this->formValidator['guestbook_signed']['text']['minLen']['error']	= $this->core->language->form_err_message;


		if (Form::isSubmitted('guestbook_signed'))
		{
			$avatar		= Form::getValue('avatar');

			if ( !$this->avatarExists($avatar) )
			{
				Form::setError('avatar', $this->core->language->form_err_avatar);
			}

			// ------------------------- FORM SUBMITTED AND VALID -------------------------
			if ( $this->validateForm('guestbook_signed') && Form::isValid('guestbook_signed') )
			{
				// ------------------------- GET FORM VALUES -------------------------
				$tblGB		= Loader::loadPluginTable('Guestbook', 'Guestbook');
				$text		= Form::getValue('text');

				$data['avatar']		= $avatar;
				$data['message']	= $text;

				$this->render = false;
				return $tblGB->save($data);	// Return Last insert ID
			}
		}
		// VIEW VARIABLES
		$this->set('language', $this->core->language);

		// VIEW OPTIONS
		$this->view('signed');
		return 0;
	}


	public function getFrontpageEntries($total = 10)
	{
		$tblGB		= Loader::loadPluginTable('Guestbook', 'Guestbook');
		$this->set('entries', $tblGB->find('all', array('limit' => $total)));
		$this->view('frontpage');
	}


	private function avatarExists($avatar)
	{
		if ( strlen($avatar) ) {
			$path = USR_PLUGINS_PATH.DS.'Guestbook'.DS.'www'.DS.'img'.DS.'avatars'.DS.$avatar;
			return is_file($path);
		} else {
			// If no avatar has been specified, OK!
			return true;
		}
	}
}
