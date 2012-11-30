<?php
class Guestbook extends PageController
{
	/**
	 *  This is a plugin
	 */
	protected $plugin = 'Guestbook';

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
		if ( $this->core->user->isLoggedIn() )
		{
			if ( $ret = $this->attachPluginBlock('bAddEntry', 'Guestbook', 'Guestbook', 'addEntrySigned') )
			{
				// Entries of logged in users are automatically approved
				$this->redirect('Guestbook', null, array(), $ret);
			}
		}
		else
		{
			if ( $this->attachPluginBlock('bAddEntry', 'Guestbook', 'Guestbook', 'addEntryUnsigned') )
			{
				// Entries of anonymous users must be approved first, so we display a note here
				$this->redirectDelayed('Guestbook', null, array(), $this->core->language->redirect_title, $this->core->language->redirect_message, 10);
			}
		}

		Javascript::addFile('/plugins/Guestbook/js/guestbook.js');

		$tblGB = Loader::LoadPluginTable('Guestbook', 'Guestbook');
		$this->set('entries', $tblGB->find('all'));

		$this->set('language', $this->core->language);
		$this->view('index');

		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'guestbook') )
		{
			$layout = Config::get('layout', 'guestbook');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}

	public function admin()
	{
		$this->error('404');
	}
}
