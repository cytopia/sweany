<?php/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweaby is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25 */abstract Class BaseController{	/* ***************************************************** VARIABLES ***************************************************** */	/*
	 * Defines the type of the controller
	 * page, layout or block.
	 * This is used to tell the language class,
	 * which section to use.	 *	 * Will be set in the according controllers.
	 */
	protected $ctrl_type;	/*	 * Defines whether the controller is used by normal	 * pages or by pages supplied by plugins.	 *	 * Default is set to false and will be overriden by	 * Plugins in the controller	 */	protected $plugin 		= false;	/*	 * Do we use a model in each controller?	 * Defaults to no and will be overritten in	 * Page Controller.	 * Each user-specified Page Controller can still override this,	 * by setting this var to false in variable namespace.	 */	protected $have_model	= false;	// Do we use a mDoes the page have a model (default no)	protected $model		= null;		// model placeholder	/*	 * The following will define the Variables, View Layout and Blocks	 * to use	 */	private $vars	= array();	// all variables parsed to the view	private $view	= null;		// the view itself to use	/*	 * If not overwritten, it will be rendered	 * into a normal view and be placed in the layout	 *	 * If you do AJAX request and want to parse raw data	 * or json, you have to set this to false in your controller function	 */	public $render	= true;	/*	 * The array filled by each controller	 * to validate forms automatically	 * with pre-defined validators	 * found in Rules.php, custom functions	 * and/or a <form_name>Validate() function	 * in the model	 */	protected $formValidator = array();	/**	 * Core Module placeholders	 */	protected $user		= null;	public $language	= null;	/* ***************************************************** CONSTRUCTOR ***************************************************** */	public function __construct()	{		/**		 * Core Module		 *		 * Attach the user class (if enabled in config.php)		 */		if ( $GLOBALS['USER_ENABLE'] )		{			$this->user		= new \Core\Init\CoreUsers;		}		/**		 * Core Module		 *
		 * If the Language Module has been enabled in config.php,
		 * make it available to the controller.
		 */
		if ( $GLOBALS['LANGUAGE_ENABLE'] )
		{
			$this->language	= new \Core\Init\CoreLanguage($this->plugin, $this->ctrl_type, get_class($this));
		}		/**
		 *
		 * Attach the Model (if desired)
		 *
		 * Note: Do not let the autoloader handle this (via = new Model)
		 * as the 'loadModel' function is optimized and much faster than the auto-loader.
		 * loadModel usually only takes 1 round.
		 *
		 * The default loadModel is to not use the block model
		 * Therefor $this->blocks needs to be true in the BlockController
		 */
		if ($this->have_model)
		{
			$this->model	= Loader::loadModel(get_class($this), $this->plugin);
		}	}	public function __destruct()	{	}	/* ***************************************************** CONTROLLER SETTER ***************************************************** */	protected function set($var, $value)	{		$this->vars[$var] = $value;	}	protected function view($view)	{		$this->view = $view;	}	/* ***************************************************** GETTER ***************************************************** */	public function getVars()	{		return $this->vars;	}	public function getView()	{		return $this->view;	}	/* ***************************************************** AUTO-FORM VALIDATOR ***************************************************** */	protected function validateForm($form_name)	{		$valid = true;		if ( Form::isSubmitted($form_name) )		{			//----------- (01.) VALIDATE FILE UPLOADS			if ( isset($_FILES) )			{				foreach ($_FILES as $field => $values)				{					$file = Form::getFile($field);					if ( $file['error'] )					{						Form::setError($field, Form::$fileError[$file['error']]);						$valid = false;					}				}			}			//----------- (0.2) VALIDATE SPECIFIED FORM RULES/CALLBACKS			if ( isset($this->formValidator[$form_name]) )			{				foreach ( $this->formValidator[$form_name] as $field => $options )				{					$value = Form::getValue($field);					foreach ($options as $opt)					{						$err = $opt['error'];						if ( isset($opt['rule']) )						{							if ( !Rules::validateRule($value, $opt['rule']) )							{								Form::setError($field, $err);								$valid = false;							}						}						else if ( isset($opt['callback']) )						{							/**							 * @Deprecated: too slow!							 *							if ( !call_user_func_array(array($this->model, $opt['callback']), array($value)) )							{								Form::setError($field, $err);								$valid = false;							}*/							// TODO: {} brackets might have a problem on windows servers.							// Need to test							if ( !$this->model->{$opt['callback']}($value) )							{								Form::setError($field, $err);								$valid = false;							}						}					}				}			}			if ( $this->have_model )			{				// check if model has a rule for this form				// <formName>Validate($form_name)				// and call if it does				$modelValidator = $form_name.'Validate';				if ( method_exists($this->model, $modelValidator) )					/**					 * @Deprecated: too slow					 *					if ( !call_user_func_array(array($this->model, $modelValidator)) )						$valid = false;					 */					if ( !$this->model->{$modelValidator}($form_name) )						$valid = false;			}			// if nothing has happened, return true			return $valid;		}		// not submitted		else		{			return false;		}	}}