<?php
class Home extends PageController
{
	/**
	 *	$hasModel defaults to true
	 *	If you do not require a Model for this controller,
	 *	then you can set it to false and save one more file from being loaded xD.
	 */
	//public $hasModel = false;


	public function index()
	{
		// Attach a block (from a plugin) to use in the view
		// Blocks can be fully functional forms with their own validation
		// Note: To attach a normal block use $this->attachBlock()
		$this->attachPluginBlock('bForumThreads', 'Forums', 'Forum', 'ActivityOverview', array(6));

		// Set variables for the view
		// This sets the language class to the view
		// The language elements $language->foo
		// are bound to this page with id="index"
		// from language xml file
		$this->set('language', $this->core->language);
		$this->set('user', $this->core->user);

		// Logging (demonstration)
		// You can log various stuff to file via this helper
		LogCat::i('some info text for the log file');
		LogCat::w('error occured here');


		// Specify the view to be used for this function.
		// You can also have multiple views for a single function via
		// $this->views()
		$this->view('index');

		// If not specified, the default layout will be used
		// the default layout view can also be modified, but then there
		// will be no layout controller available.
		// default view is usr/layout/view/template.tpl.php
		// The view for Layouts->FrontPage is specified
		// in Layouts Controller in the function 'FrontPage'
		// This function loads the login/logout block
		$this->layout('Layouts', 'FrontPage');

		// Loading additional css file
		// Just for demonstration purposes
		Css::addFile('/css/test.css');


		// ADD TEMPLATE ELEMENTS
		// If not set, each page will use the
		// default title, keywords, description
		// from config.php
		// Set only for demonstration purposes.

		// Set the page title
		HtmlTemplate::setTitle('Home');

		// Set seo keywords
		HtmlTemplate::setKeywords('sweany php, mvc framework');

		// Set seo description
		HtmlTemplate::setDescription('Sweany PHP - performant mvc framework');
	}
}
