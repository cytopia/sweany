<?php
class Contact extends PageController
{
	/**
	 *  This is a plugin
	 */
	protected $plugin = 'Contact';

	/**
	 * We do not need a model here
	 */
	protected $have_model = false;




	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/
	public function index()
	{
		$submitted = $this->attachBlock('contactForm', 'Contact', 'Contact', 'addContact');

		 //form was submitted successfully
		 //show info and the redirect user to the start page
		 //after 5 seconds delay
		if ($submitted)
		{
			$this->render = false;
			$this->redirectDelayedHome($this->language->submittedHead, $this->language->submittedBody, 5);
			return;
		}
		$this->set('language', $this->language);
		$this->view('contact.tpl.php');
	}
}
