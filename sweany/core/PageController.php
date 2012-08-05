<?php/** * Abstract parent for page controller * * * Sweany: MVC-like PHP Framework with blocks and tables (entities) * Copyright 2011-2012, Patu * * Licensed under The MIT License * Redistributions of files must retain the above copyright notice. * * @copyright	Copyright 2011-2012, Patu * @link		none yet * @package		sweany.sys * @author		Patu * @license		MIT License (http://www.opensource.org/licenses/mit-license.php) * @version		0.7 2012-07-29 13:25 * */abstract Class PageController extends BaseController{	/* ***************************************************** VARIABLES ***************************************************** */	/*	 * The following will define the Variables, View Layout and Blocks	 * to use	 *	 * Already defined in BaseController:	 *	private $vars	= array();	// all variables parsed to the view	 *	private $view	= null;		// the view itself to use	 */	private $layout	= null;		// the layout file to render the view into	private $blocks	= array();	// pre-rendered blocks (if any)	/*	 * If not overwritten, it will be rendered	 * into a normal view and be placed in the layout	 *	 * If you do AJAX request and want to parse raw data	 * or json, you set this to false	 */	public $render	= true;	protected $model		= null;		// model	protected $have_model	= true;		// Does the page have a model (default yes)	/*	 * The array filled by each controller	 * to validate forms automatically	 * with pre-defined validators	 * found in Rules.php, custom functions	 * and/or a <form_name>Validate() function	 * in the model	 */	protected $formValidator = array();	/*	 *  callback type used for the $ob_handler	 *  In Debug Mode we still want to be able to see errors	 *  In Production Mode we try to use compression	 *  (see constructor)	 */	private $ob_callback = null;	/*	 * Array keeping track of the last page that has been visited on this Site	 *	 * If no last page exists, it will be null, otherwise:	 *	 * $lastPage['controller']	 * $lasgPage['method']	 * $lastPage['params']	 *	 */	private $lastPage = null;	/* ***************************************************** CONSTRUCTOR ***************************************************** */	public function __construct()	{		// Call the Parent Constructor		parent::__construct();		// set auto render to true		$this->render	= true;		// set default layout which is the default function in the LayoutController		//$this->layout	= 'defaultLayout';		/*		* Initialize the Model		*		* Do not let the autoloader handle this (via = new Model)		* as the 'loadModel' function is optimized and much faster than the auto-loader.		* loadModel usually only takes 1 round.		*		* The default loadModel is to not use the block model		* There fore $this->blocks needs to be true in the BlockController		*/		if ($this->have_model)		{			$this->model	= Loader::loadModel(get_class($this), $this->plugin);		}		/*		 *  initialize the callback function here once		 *  otherwise we would need to check every time		 *  attachBlock is called		 *  In production mode we try to use compression from ob_gzhandler		 */		//$this->ob_callback = (Settings::$debugLevel) ? 'ob_error_handler' : 'ob_gzhandler';		$this-> _trackPreviousPage();		$this->language	= new \Core\Init\CoreLanguage($this->plugin, 'page', get_class($this));	}	public function __desctruct()	{		parent::__destruct();	}	/* ***************************************************** CONTROLLER SETTER ***************************************************** */	protected function layout($class, $method)	{		$this->layout = array($class, $method);	}	protected function attachBlock($varName, $blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams = array())	{		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )			$start = getmicrotime();		$output = Render::block($blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams);		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )			SysLog::i('Attach Block', '(Done) | [to Page] from: '.$blockPluginName.'\\'.$blockControllerName.'::'.($blockControllerName).'->'.$blockMethodName, null, $start);		// 08) store block into array		$this->blocks[$varName]	= $output['content'];		return $output['return'];	}	/* ***************************************************** INDEX GETTER ***************************************************** */	public function getBlocks()	{		return $this->blocks;	}	public function getLayout()	{		return $this->layout;	}	public function isPlugin()	{		return $this->plugin;	}	/* ***************************************************** REDIRECTS ***************************************************** */	/**	 *	 * Redirect to a different page by Ctl/Method	 * Make sure to encode the parameter values nicely	 */	protected function redirect($controller, $method = null, $params = array())	{		$args = /*$this->__url_encode_params*/implode('/', $params);		$link = '/'.$controller;		$link.= ($method) ? (strlen($args) ? '/'.$method.'/'.$args : '/'.$method) : '';		// if debug is on, do not redirect, but show the link instead		if ( \Core\Init\CoreSettings::$showFwErrors )		{			echo '<font color="red">Redirect Call: </font><a href="'.$link.'">'.$link.'</a>';			\SysLog::show();		}		else		{			header('Location: '.$link);			exit();		}	}	/**	 *	 * Redirect to front page	 */	protected function redirectHome()	{		$this->redirect($GLOBALS['DEFAULT_CONTROLLER'], $GLOBALS['DEFAULT_METHOD']);	}	/**	 * Redirect to the page you came from	 * TODO: This is still buggy, If user comes from external page and enters	 * redirecting or if he comes from redirecting and goes to redirecting	 * manually.	 */	protected function redirectBack()	{		$controller = $this->lastPage['controller'];		$method		= $this->lastPage['method'];		$params		= $this->lastPage['params'];		$this->redirect($controller, $method, $params);	}	protected function redirectDelayed($controller, $method, $params, $title, $body, $delay = 5)	{		$params			= (is_array($params)) ? $params : array();		$info['url']	= '/'.$controller.'/'.$method.'/'.implode('/', $params);		$info['delay']	= $delay;		$info['title']	= $title;		$info['body']	= $body;		\Core\Init\CoreSession::set('info_message_data', $info);		$this->redirect($GLOBALS['DEFAULT_INFO_MESSAGE_URL']);	}	protected function redirectDelayedHome($title, $body, $delay = 5)	{		$this->redirectDelayed($GLOBALS['DEFAULT_CONTROLLER'], $GLOBALS['DEFAULT_METHOD'], null, $title, $body, $delay);	}	/**	 * Redirect to the page you came from	 * TODO: This is still buggy, If user comes from external page and enters	 * redirecting or if he comes from redirecting and goes to redirecting	 * manually.	 */	protected function redirectDelayedBack($title, $body, $delay = 5)	{		$controller = $this->lastPage['controller'];		$method		= $this->lastPage['method'];		$params		= $this->lastPage['params'];		$this->redirectDelayed($controller, $method, $params, $title, $body, $delay);	}	/* ***************************************************** PRIVATES ***************************************************** */	private function _trackPreviousPage()	{		if ( \Core\Init\CoreSession::exists('_navigation') )		{			$navigation		= \Core\Init\CoreSession::get('_navigation');			$this->lastPage	= $navigation['thisPage'];		}		$navigation['thisPage'] = array('controller' => \Core\Init\CoreUrl::getController(), 'method' => \Core\Init\CoreUrl::getMethod(), 'params' => \Core\Init\CoreUrl::getParams());		$navigation['lastPage'] = $this->lastPage;		\Core\Init\CoreSession::set('_navigation', $navigation);	}}?>