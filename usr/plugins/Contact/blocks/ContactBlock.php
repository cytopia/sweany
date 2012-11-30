<?php
class ContactBlock extends BlockController
{
	protected $plugin			= 'Contact';


	/* ***************************************** FORM VALIDATOR ******************************************/
	protected $formValidator = array(
		// Form for adding contact
		'form_add_contact'	=> array(
			'name'	=> array(
				'minLen'	=> array(
					'rule'		=> array('minLen', 1),
					'error'		=> '',
				),
			),
			'message'	=> array(
				'minLen'	=> array(
					'rule'	=> array('minLen', 5),
					'error'	=> '',
				),
			),
			'subject'	=> array(
				'between'	=> array(
					'rule'	=> array('between', array(1,4)),
					'error'	=> '',
				),
			),
			'email'	=> array(
				'isEmail'	=> array(
					'rule'	=> array('isEmail'),
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
	public function addContact()
	{
		//------ Override Form validator with appropriate language
		$this->formValidator['form_add_contact']['name']['minLen']['error']		= $this->core->language->form_err_name;
		$this->formValidator['form_add_contact']['message']['minLen']['error']	= $this->core->language->form_err_message;
		$this->formValidator['form_add_contact']['subject']['between']['error']	= $this->core->language->form_err_subject;
		$this->formValidator['form_add_contact']['email']['isEmail']['error']	= $this->core->language->form_err_email;

		//------ Build subject Array
		$subArr[0]= $this->core->language->form_input_subject;

		$size = sizeof($this->core->language->subjects);
		for($i=0; $i<$size; $i++)
		{
			$subArr[$i+1] = (string)$this->core->language->subjects->$i;
		}

		//------ Adjust Subject Form Rule (according to the number of subjects)
		$this->formValidator['form_add_contact']['subject']['between']['rule'] = array('between', array(1,$size));


		// ------------------------- FORM SUBMITTED AND VALID -------------------------
		if ( $this->validateForm('form_add_contact')  )
		{
			// ------------------------- GET FORM VALUES -------------------------
			$tblContact	= Loader::loadPluginTable('Contact', 'Contact');
			$name		= Form::getValue('name');
			$email		= Form::getValue('email');
			$subject_id	= Form::getValue('subject');
			$message	= Form::getValue('message');
			$subject	= $subArr[$subject_id];

			$data['name']		= $name;
			$data['email']		= $email;
			$data['subject']	= $subject;
			$data['message']	= $message;

			$tblContact->save($data);
			$this->render = false;
			return 1;
		}

		// VIEW VARIABLES
		$this->set('language', $this->core->language);
		$this->set('subject', $subArr);

		// VIEW OPTIONS
		$this->view('add_contact');
		return 0;
	}



	/**
	 *
	 * Show an overview of received Contact messages
	 *
	 * @param	string	$controller		The controller call to show a single message
	 * @param	string	$method			The method call to show a single message
	 */
	public function adminShowAll($controller, $method)
	{
		$tblContact	= Loader::loadPluginTable('Contact', 'Contact');
		$messages	= $tblContact->find('all', array('order' => array('created' => 'DESC')));

		$this->set('controller', $controller);
		$this->set('method', $method);
		$this->set('messages', $messages);

		$this->view('admin_show_all');
	}


	/**
	 *
	 * Shows a single contact message
	 * and marks it as read in case it exists.
	 *
	 * @param	integer	id			Id of the contact message to display
	 * @return	integer	exists		Returns 1 if the message exists and -1 if it does not
	 */
	public function adminShowOne($id)
	{
		$tblContact	= Loader::loadPluginTable('Contact', 'Contact');
		$message	= $tblContact->load($id);

		// Message does not exist
		if ( !count($message) )
		{
			$this->render = false;
			return -1;
		}

		// mark current contact message as read
		$tblContact->markRead($id);

		$this->set('message', $message);
		$this->view('admin_show_one');

		// return success
		return 1;
	}
}
