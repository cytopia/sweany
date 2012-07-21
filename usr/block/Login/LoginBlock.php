<?phpclass LoginBlock extends BlockController{	public $helpers = array('Html', 'Form');	/* ***************************************** FORM VALIDATOR ******************************************/	protected $formValidator = array();	/* **********************************************************************************************************************	*	*   F U N C T I O N S	*	* **********************************************************************************************************************/	public function view_loginLinkBox($loginCtl, $loginMethod, $signupCtl, $signupMethod)	{		// VIEW VARIABLES		$this->set('loginCtl', $loginCtl);		$this->set('loginMethod', $loginMethod);		$this->set('signupCtl', $signupCtl);		$this->set('signupMethod', $signupMethod);		$this->set('language', $this->language);		// VIEW OPTIONS		$this->view('login_link_box.tpl.php');	}	public function form_addContact($subjects)	{		//------ Override Form validator with appropriate language		$this->formValidator['form_add_contact']['name']['minLen']['error']		= $this->language->form_err_name;		$this->formValidator['form_add_contact']['message']['minLen']['error']	= $this->language->form_err_message;		$this->formValidator['form_add_contact']['subject']['between']['error']	= $this->language->form_err_subject;		$this->formValidator['form_add_contact']['email']['isEmail']['error']	= $this->language->form_err_email;		//------ Build subject Array		$subArr[0]['id']	= 0;		$subArr[0]['value'] = $this->language->form_input_subject;		$size = sizeof($subjects);		for($i=0; $i<$size; $i++)		{			$subArr[$i+1]['id']		= ($i+1);			$subArr[$i+1]['value']	= $subjects[$i];		}		//------ Adjust Subject Form Rule (according to the number of subjects)		$this->formValidator['form_add_contact']['subject']['between']['rule'] = array('between', array(1,$size));		// ------------------------- FORM SUBMITTED AND VALID -------------------------		if ( $this->validateForm('form_add_contact')  )		{			// ------------------------- GET FORM VALUES -------------------------			$tblContact	= Loader::loadTable('Contact');			$name		= $this->form->getValue('name');			$email		= $this->form->getValue('email');			$subject_id	= $this->form->getValue('subject');			$message	= $this->form->getValue('message');			$subject	= $subArr[$subject_id]['value'];			$name		= Strings::removeTags($name);			$email		= Strings::removeTags($email);			$message	= Strings::removeTags($message);			$tblContact->add($this->user->id(), $name, $email, $subject, $message);			return 1;		}		// VIEW VARIABLES		$this->set('language', $this->language);		$this->set('subject', $subArr);		// VIEW OPTIONS		$this->view('contact.tpl.php');		return 0;	}}?>