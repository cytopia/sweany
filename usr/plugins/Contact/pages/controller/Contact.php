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
	protected $hasModel = false;




	/* **********************************************************************************************************************
	*
	*   F U N C T I O N S
	*
	* **********************************************************************************************************************/
	public function index()
	{
		$submitted		= $this->attachPluginBlock('bContactForm', 'Contact', 'Contact', 'addContact');

		 //form was submitted successfully
		 //show info and the redirect user to the start page
		 //after 5 seconds delay
		if ($submitted)
		{
			$this->render = false;
			$this->redirectDelayedHome($this->core->language->submittedHead, $this->core->language->submittedBody, 5);
			return;
		}
		$this->set('language', $this->core->language);
		$this->view('index');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'contact') )
		{
			$layout = Config::get('layout', 'contact');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}
