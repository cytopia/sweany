<?php
class Faq extends PageController
{
	// This is a plugin
	protected $plugin = 'Faq';

	// Dont need a model
	protected $hasModel = false;


	public function index()
	{
		$tblFaq		= Loader::loadPluginTable('FaqSection', 'Faq');
		$entries	= $tblFaq->find('all');

		// TODO: add self-defined CSS

		// ADD TEMPLATE ELEMENTS
		HtmlTemplate::setTitle($this->core->language->pageTitle);

		// VIEW VARIABLES
		$this->set('entries', $entries);

		// VIEW
		$this->view('index');
		
		// LAYOUT OPTIONS
		if  ( Config::exists('layout', 'faq') )
		{
			$layout = Config::get('layout', 'user');
			$this->layout($layout[0], $layout[1], isset($layout[3]) ? $layout[3] : array());
		}
	}
}
