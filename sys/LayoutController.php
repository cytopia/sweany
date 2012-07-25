<?phpclass LayoutController extends BaseController{	private $blocks	= array();	// pre-rendered blocks (if any)			public function __construct()	{		parent::__construct();		// default Layout		$this->view($GLOBALS['DEFAULT_LAYOUT']);	}	/* ***************************************************** SETTER ***************************************************** */

	protected function attachBlock($varName, $blockControllerName, $blockMethodName, $blockMethodParams = array())
	{
		if ( Settings::$debugLevel )
			$start = getmicrotime();

		$output = Render::block($blockControllerName, $blockMethodName, $blockMethodParams);

		if ( Settings::$debugLevel )
			Log::setInfo('Render Block', $blockControllerName.'::'.($blockControllerName).'->'.$blockMethodName, null, $start);
	
		// 08) store block into array
		$this->blocks[$varName]	= $output['content'];
		return $output['return'];
	}

	
	/* ***************************************************** GETTER ***************************************************** */
	
	public function getBlocks()
	{
		return $this->blocks;
	}}