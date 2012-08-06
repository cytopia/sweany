<?php/** * Abstract top parent for controller * * * Sweany: MVC-like PHP Framework with blocks and tables (entities) * Copyright 2011-2012, Patu * * Licensed under The MIT License * Redistributions of files must retain the above copyright notice. * * @copyright	Copyright 2011-2012, Patu * @link		none yet * @package		sweany.sys * @author		Patu * @license		MIT License (http://www.opensource.org/licenses/mit-license.php) * @version		0.7 2012-07-29 13:25 * */abstract Class BaseController{	/* ***************************************************** VARIABLES ***************************************************** */	/*	 * Defines whether the controller is used by normal	 * pages or by pages supplied by plugins.	 *	 * Default is set to false and will be overriden by	 * Plugins in the controller	 */	protected $plugin 		= false;	protected $have_model	= false;		// Does the page have a model (default no)	/*	 * The following will define the Variables, View Layout and Blocks	 * to use	 */	private $vars	= array();	// all variables parsed to the view	private $view	= null;		// the view itself to use	/*	 * If not overwritten, it will be rendered	 * into a normal view and be placed in the layout	 *	 * If you do AJAX request and want to parse raw data	 * or json, you set this to false	 */	public $render	= true;	public $language	= null;	/*	 * The array filled by each controller	 * to validate forms automatically	 * with pre-defined validators	 * found in Rules.php, custom functions	 * and/or a <form_name>Validate() function	 * in the model	 */	protected $formValidator = array();	/**	 * Required Classes	 */	protected $user		= null;	/* ***************************************************** CONSTRUCTOR ***************************************************** */	public function __construct()	{		$this->user		= new \Core\Init\CoreUsers;	}	public function __destruct()	{	}	/* ***************************************************** CONTROLLER SETTER ***************************************************** */	protected function set($var, $value)	{		$this->vars[$var] = $value;	}	protected function view($view)	{		$this->view = $view;	}	/* ***************************************************** GETTER ***************************************************** */	public function getVars()	{		return $this->vars;	}	public function getView()	{		return $this->view;	}	/* ***************************************************** AUTO-FORM VALIDATOR ***************************************************** */	protected function validateForm($form_name)	{		$valid = true;		if ( Form::isSubmitted($form_name) )		{			//----------- (01.) VALIDATE FILE UPLOADS			if ( isset($_FILES) )			{				foreach ($_FILES as $field => $values)				{					$file = Form::getFile($field);					if ( $file['error'] )					{						Form::setError($field, Form::$fileError[$file['error']]);						$valid = false;					}				}			}			//----------- (0.2) VALIDATE SPECIFIED FORM RULES/CALLBACKS			if ( isset($this->formValidator[$form_name]) )			{				foreach ( $this->formValidator[$form_name] as $field => $options )				{					$value = Form::getValue($field);					foreach ($options as $opt)					{						$err = $opt['error'];						if ( isset($opt['rule']) )						{							if ( !Rules::validateRule($value, $opt['rule']) )							{								Form::setError($field, $err);								$valid = false;							}						}						else if ( isset($opt['callback']) )						{							/**							 * @Deprecated: too slow!							 *							if ( !call_user_func_array(array($this->model, $opt['callback']), array($value)) )							{								Form::setError($field, $err);								$valid = false;							}*/							if ( !$this->model->{$opt['callback']}($value) )							{								Form::setError($field, $err);								$valid = false;							}						}					}				}			}			if ( $this->have_model )			{				// check if model has a rule for this form				// <formName>Validate($form_name)				// and call if it does				$modelValidator = $form_name.'Validate';				if ( method_exists($this->model, $modelValidator) )					/**					 * @Deprecated: too slow					 *					if ( !call_user_func_array(array($this->model, $modelValidator)) )						$valid = false;					 */					if ( !$this->model->{$modelValidator}($form_name) )						$valid = false;			}			// if nothing has happened, return true			return $valid;		}		// not submitted		else		{			return false;		}	}}