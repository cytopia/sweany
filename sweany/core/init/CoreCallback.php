<?php
/**
 * This core module will extract the class name, function name
 * and function parameter values from the given URL request.
 *
 *
 * Sweany: MVC-like PHP Framework with blocks and tables (entities)
 * Copyright 2011-2012, Patu
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
namespace Core\Init;

class CoreCallback extends CoreAbstract
{

	private static $object	= array();

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	/**
	 *
	 * Sets the Controller, Function and Params to the $object - depending on the given URL parameter
	 *
	 * Security Checks:		Checks if requested Class/Function is allowed to execute
	 * Error Handling:		asigns Default- or Error Controller/Method
	 *
	 */
	public static function initialize()
	{
		$controller	= \Core\Init\CoreUrl::getController();
		$method		= \Core\Init\CoreUrl::getMethod();
		$params		= \Core\Init\CoreUrl::getParams();

		//------------- 00) Check if this is a framework internal call structure
		/* TODO
		if ( Router::isDefined($controller, $method) )
		{
			\Log::setInfo('Callback', 'Internal request caught by Router: '.$controller.'->'.$method);
		}
		*/

		if ( \Core\Init\CoreUrl::$request == $GLOBALS['DEFAULT_INFO_MESSAGE_URL'] )
		{
			\SysLog::i('Callback', 'Internal request: '.\Core\Init\CoreUrl::$request);

			// Load the Framework Default Page Controller
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefault.php');
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefaultModel.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'info_message',
				'params'	=> array(\Core\Init\CoreUrl::$request),
			);
			return true;
		}


		//------------- 01) No controller specified, so start with the default entry point
		else if ( !$controller )
		{
			\SysLog::w('Callback', 'no url request made - using default controller');

			require(PAGES_CONTROLLER_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php');
			require(PAGES_MODEL_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'Model.php');

			self::$object = array(
				'class'		=> $GLOBALS['DEFAULT_CONTROLLER'],
				'method'	=> $GLOBALS['DEFAULT_METHOD'],
				'params'	=> array(),
			);
			return true;
		}

		//------------- 02) Controller does not have specified function
		//
		// Now we have to check whether using redirect, error, robots.txt or not-found
		// therefore let the error controller handle it
		else if ( !self::_isCallable($controller, $method) )
		{
			\SysLog::w('Callback', 'Wrong request: class &lt;'.$controller.'&gt; and method &lt;'.$method.'&gt; not found.');


			// Load the Framework Default Page Controller
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefault.php');
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefaultModel.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'url_not_found',
				'params'	=> array(\Core\Init\CoreUrl::$request),
			);
			return true;
		}

		//------------- 03) Security pre-caution:
		// Make sure that it is a class that has 'Controller.php' as his mother and not
		// any other user-defined class
		else if ( !self::_isControllerClass($controller) )
		{
			\SysLog::w('Callback', 'Wrong request: class &lt;'.$controller.'&gt; is not a Controller class.');

			require(PAGES_CONTROLLER_PATH.DS.$GLOBALS['ERROR_CONTROLLER'].'.php');
			require(PAGES_MODEL_PATH.DS.$GLOBALS['ERROR_CONTROLLER'].'Model.php');

			self::$object = array(
				'class'		=> $GLOBALS['ERROR_CONTROLLER'],
				'method'	=> $GLOBALS['ERROR_METHOD'],
				'params'	=> array(Url::$request),
			);
			return true;
		}

		//------------- 04)  Security pre-caution:
		// All functions defined in 'Controller.php' itself are inherited to
		// the xxxController Class, but are not allowed to be called
		else if ( self::_methodIsForbidden($method) )
		{
			\SysLog::w('Callback', 'Method &lt;'.$method.'&gt; is not allowed to be called');

			// Load the Framework Default Page Controller
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefault.php');
			require_once(CORE_PAGES_PATH.DS.'FrameworkDefaultModel.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'url_not_found',
				'params'	=> array(Url::$request),
			);
			return true;
		}

		//------------- 05)  OK:
		// Everyhing went fine
		else
		{
			self::$object = array(
				'class'		=> $controller,
				'method'	=> $method,
				'params'	=> $params,
			);
			return true;
		}
	}



	/* ******************************************** RETURN THE OBJECT ********************************************/
	public static function getObject()
	{
		return self::$object;
	}




	/************************************************** PRIVATE FUNCTIONS **************************************************/

	/**
	 *
	 * Does the class or function actually exist
	 * and are they 'public', so they can be called?
	 *
	 */
	private static function _isCallable($class, $method)
	{
		if (!class_exists($class))
		{
			\SysLog::w('Callback', 'class &lt;'.$class.'&gt; does not exist.');
			return false;
		}
		if ( !method_exists($class, $method) )
		{
			\SysLog::w('Callback', 'method &lt;'.$method.'&gt; does not exist in class &lt;'.$class.'&gt;');
			return false;
		}
		/*
		 * Only allow public functions to be called
		 *
		 * produces E_STRICT WARNING on older PHP Versions, so we need the '@'
		 */
		if ( !@is_callable(array($class, $method)) )
		{
			\SysLog::w('Callback', 'method &lt;'.$method.'&gt; is not publically callable in class &lt;'.$class.'&gt;');
			return false;
		}

		return true;
	}

	/**
	 *
	 * Does the class extend 'Controller.php'?
	 * Otherwise it is a class/function from somewhere
	 * else and we do not want it to be executed
	 *
	 */
	private static function _isControllerClass($class)
	{
		if ( get_parent_class($class) != 'PageController' )
		{
			return false;
		}
		return true;
	}

	/**
	*
	* All methods in 'Controller.php' itself are
	* forbidden.
	*
	* E.g.: We do not want the user to call getView
	* 		or even worse set() to override variables
	*
	*/
	private static function _methodIsForbidden($method)
	{
		// All methods in the framework Controller itself
		// are not allowed to be called
		$pageCtlMethods		= get_class_methods('PageController');
		$baseCtlMethods		= get_class_methods('BaseController');
		$blacklistMethods	= array_merge($pageCtlMethods, $baseCtlMethods);

		foreach ($blacklistMethods as $forbidden)
		{
			if ($method == $forbidden)
			{
				return true;
			}
		}
		return false;
	}


	private static function _blockMethodIsCallable($blockCtrl, $blockMthd)
	{
		// TODO: security check
		return true;
	}
}
