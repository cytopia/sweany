<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core.init
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * This core module will extract the class name, function name
 * and function parameter values from the given URL request.
 *
 */
namespace Sweany;

class Router extends aBootTemplate
{

	private static $object	= array();

	private static $log_section = 'core';
	private static $log_title	= 'Router';

	/**
	 *
	 * Holds the decision whether or not the page was
	 * visit by the user on purpose. It will be used later
	 * to track the page visits and be able to redirect the user
	 * to the last visited page.
	 *
	 * If for example the call is made to a Default Controller
	 * 'not_found', 'info_message' or 'settings', etc. then
	 * this is not a visitable page, as we do not want to redirect
	 * the user back to one of those pages
	 *
	 * @param boolean $visitablePage
	 */
	public static $visitablePage = false;

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	/**
	 *
	 * Sets the Controller, Function and Params to the $object - depending on the given URL parameter
	 *
	 * Security Checks:		Checks if requested Class/Function is allowed to execute
	 * Error Handling:		asigns Default- or Error Controller/Method
	 *
	 */
	public static function initialize($options = null)
	{
		$controller	= \Sweany\Url::getController();
		$method		= \Sweany\Url::getMethod();
		$params		= \Sweany\Url::getParams();

		//------------- 00) Check if this is a framework internal call structure
		/* TODO
		if ( Router::isDefined($controller, $method) )
		{
			\Log::setInfo('Callback', 'Internal request caught by Router: '.$controller.'->'.$method);
		}
		*/

		if ( \Sweany\Url::$request == $GLOBALS['DEFAULT_INFO_MESSAGE_URL'] )
		{
			\Sweany\SysLog::i(self::$log_section, self::$log_title, 'Internal request: '.\Sweany\Url::$request);

			// Load the Framework Default Page Controller
			require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'info_message',
				'params'	=> array(\Sweany\Url::$request),
			);
			return true;
		}
		else if ( $controller == $GLOBALS['DEFAULT_SETTINGS_URL'] )
		{
			\Sweany\SysLog::i(self::$log_section, self::$log_title, 'Internal request: '.\Sweany\Url::$request);

			// Load the Framework Default Page Controller
			require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

			// push the method into params, as we do not have a method here
			// and the 2nd param (normally method) is actually a parameter
			array_unshift($params, $method);
			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'change_settings',
				'params'	=> $params,
			);
			return true;
		}
		//------------- 01) No controller specified, so start with the default entry point
		else if ( !$controller )
		{
			\Sweany\SysLog::i(self::$log_section, self::$log_title, 'no url request made - using default controller');

			require(PAGES_CONTROLLER_PATH.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php');

			// Check if the Default Controller is authorized to be viewed
			// by the current user.
			// If not display an error not found message, to hide the admin area
			if ( !$GLOBALS['DEFAULT_CONTROLLER']::isAuthorized() )
			{
				// Load the Framework Default Page Controller
				require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

				self::$object = array(
					'class'		=> 'FrameworkDefault',
					'method'	=> 'url_not_found',
					'params'	=> array(\Sweany\Url::$request),
				);
			}
			else
			{
				self::$object = array(
					'class'		=> $GLOBALS['DEFAULT_CONTROLLER'],
					'method'	=> $GLOBALS['DEFAULT_METHOD'],
					'params'	=> array(),
				);
			}
			self::$visitablePage = true;
			return true;
		}
		else if ( !$controller::isAuthorized() )
		{
			\Sweany\SysLog::w(self::$log_section, self::$log_title, '[Not Authorized] - Faking Not Found in: class &lt;'.$controller.'&gt; and method &lt;'.$method.'&gt;');

			// Load the Framework Default Page Controller
			require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'url_not_found',
				'params'	=> array(\Sweany\Url::$request),
			);
			return true;
		}

		//------------- 02) Controller does not have specified function
		//
		// Now we have to check whether using redirect, error, robots.txt or not-found
		// therefore let the error controller handle it
		else if ( !self::_isCallable($controller, $method) )
		{
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'Wrong request: class &lt;'.$controller.'&gt; and method &lt;'.$method.'&gt; not found.');


			// Load the Framework Default Page Controller
			require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

			self::$object = array(
				'class'		=> 'FrameworkDefault',
				'method'	=> 'url_not_found',
				'params'	=> array(\Sweany\Url::$request),
			);
			return true;
		}

		//------------- 03) Security pre-caution:
		// Make sure that it is a class that has 'Controller.php' as his mother and not
		// any other user-defined class
		else if ( !self::_isControllerClass($controller) )
		{
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'Wrong request: class &lt;'.$controller.'&gt; is not a Controller class.');

			require(PAGES_CONTROLLER_PATH.DS.$GLOBALS['ERROR_CONTROLLER'].'.php');

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
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'Method &lt;'.$method.'&gt; is not allowed to be called');

			// Load the Framework Default Page Controller
			require_once(CORE_CONTROLLER.DS.'FrameworkDefault.php');

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
			\Sweany\SysLog::i(self::$log_section, self::$log_title, 'Normal Request');

			self::$object = array(
				'class'		=> $controller,
				'method'	=> $method,
				'params'	=> $params,
			);
			self::$visitablePage = true;
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
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'class &lt;'.$class.'&gt; does not exist.');
			return false;
		}
		if ( !method_exists($class, $method) )
		{
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'method &lt;'.$method.'&gt; does not exist in class &lt;'.$class.'&gt;');
			return false;
		}
		/*
		 * Only allow public functions to be called
		 *
		 * produces E_STRICT WARNING on older PHP Versions, so we need the '@'
		 */
		if ( !@is_callable(array($class, $method)) )
		{
			\Sweany\SysLog::w(self::$log_section, self::$log_title, 'method &lt;'.$method.'&gt; is not publically callable in class &lt;'.$class.'&gt;');
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
