<?phpclass ContactBlock extends BlockController{	protected $plugin			= 'Contact';	/* ***************************************** FORM VALIDATOR ******************************************/	protected $formValidator = array(		// Form for adding contact		'form_add_contact'	=> array(			'name'	=> array(				'minLen'	=> array(					'rule'		=> array('minLen', 1),					'error'		=> '',				),			),			'message'	=> array(				'minLen'	=> array(					'rule'	=> array('minLen', 5),					'error'	=> '',				),			),			'subject'	=> array(				'between'	=> array(					'rule'	=> array('between', array(1,4)),					'error'	=> '',				),			),			'email'	=> array(				'isEmail'	=> array(					'rule'	=> array('isEmail'),					'error'	=> '',				),			),		),	);	/* **********************************************************************************************************************	*	*   F U N C T I O N S	*	* **********************************************************************************************************************/	public function addContact()	{		//------ Override Form validator with appropriate language		$this->formValidator['form_add_contact']['name']['minLen']['error']		= $this->language->form_err_name;		$this->formValidator['form_add_contact']['message']['minLen']['error']	= $this->language->form_err_message;		$this->formValidator['form_add_contact']['subject']['between']['error']	= $this->language->form_err_subject;		$this->formValidator['form_add_contact']['email']['isEmail']['error']	= $this->language->form_err_email;		//------ Build subject Array		$subArr[0]['id']	= 0;		$subArr[0]['value'] = $this->language->form_input_subject;		$size = sizeof($this->language->subjects);		for($i=0; $i<$size; $i++)		{			$subArr[$i+1]['id']		= ($i+1);			$subArr[$i+1]['value']	= $this->language->subjects[$i];		}		//------ Adjust Subject Form Rule (according to the number of subjects)		$this->formValidator['form_add_contact']['subject']['between']['rule'] = array('between', array(1,$size));		// ------------------------- FORM SUBMITTED AND VALID -------------------------		if ( $this->validateForm('form_add_contact')  )		{			// ------------------------- GET FORM VALUES -------------------------			$tblContact	= Loader::loadPluginTable('Contact', 'Contact');			$name		= Form::getValue('name');			$email		= Form::getValue('email');			$subject_id	= Form::getValue('subject');			$message	= Form::getValue('message');			$subject	= $subArr[$subject_id]['value'];			$name		= Strings::removeTags($name);			$email		= Strings::removeTags($email);			$message	= Strings::removeTags($message);			$tblContact->add($this->user->id(), $name, $email, $subject, $message);			$this->render = false;			return 1;		}		// VIEW VARIABLES		$this->set('language', $this->language);		$this->set('subject', $subArr);		// VIEW OPTIONS		$this->view('add_contact.tpl.php');		return 0;	}}