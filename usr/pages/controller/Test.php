<?phpclass Test extends PageController{	//	// If you do not need a model, set this to false.	// It will speed up things, as the file does not to be loaded/parsed	//	// protected $have_model = false;	public function index()	{		// specify the view to be used for this function		$this->view('index');		// If not specified, the default layout will be used		// the default layout view can also be modified, but then there		// will be no layout controller available.		// default view is usr/layout/view/template.tpl.php		// The view for Layouts->FrontPage is specified		// in Layouts Controller in the function 'FrontPage'		// This function loads the login/logout block		$this->layout('Layouts', 'FrontPage');		$visitor = Loader::loadTable('Visitors');		debug($visitor->load(1019, array('id', 'url', 'referer', 'fk_user_id')));				$selectBoxData = array(array('id'=>0, 'value'=>'-select-'), array('id'=>1, 'value'=>'test1'), array('id'=>2, 'value'=>'test2'));		$this->set('language', $this->language);		$this->set('selectBoxData', $selectBoxData);	}}