<?phpclass Groups extends Controller{	public $package = 'Groups';		public $helpers = array('Html', 'HtmlTemplate','Form');	/* ***************************************** FORM VALIDATOR ******************************************/	protected $formValidator = array(	);	/* **********************************************************************************************************************	*	*   F U N C T I O N S	*	* **********************************************************************************************************************/	public function show($groupid = null)	{		// ADD TEMPLATE ELEMENTS		//$this->htmltemplate->setTitle('Nachrichten Box');		// ADD CSS		// VIEW VARIABLES		$this->set('active','Groups');		// VIEW OPTIONS			$this->view('show.tpl.php');	}		public function index()	{		// ADD TEMPLATE ELEMENTS		//$this->htmltemplate->setTitle('Nachrichten Box');		// ADD CSS		// VIEW VARIABLES		$this->set('active','Groups');		// VIEW OPTIONS		$this->view('index.tpl.php');		}}?>