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
 * @package		sweany.core
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Abstract parent for page controller
 */
abstract Class PageController extends BaseController
{

	/* ***************************************************** VARIABLES ***************************************************** */

	/*
	 *	Is this a sweany built-in core Controller?
	 *
	 *	We need this in order to determine, which view
	 *	to use for built-in controllers
	 *
	 *	@param	boolean	$isBuiltIn
	 */
	public $isCore = false;


	/*
	 * Defines the type of the controller
	 * page, layout or block.
	 * This is used to tell the language class,
	 * which section to use
	 */
	protected $ctrl_type = 'page';


	/*
	 *	What Layout to use?
	 *
	 *	@param	string|mixed[]	Layout
	 */
	private $layout	= null;		// the layout file to render the view into



	/**
	 * Additionally to $view, a PageController also
	 * has $views. This is an associative array of multiple
	 * views that can be rendered into a layout.
	 *
	 * @var mixed[]	$views
	 * 	$views['key'] = 'view name'
	 */
	private $views	= null;



	/*
	 * DO USE a model by default in page controllers
	 *
	 * Can still be overritten individually by project specific controller pages
	 * by setting $hasModel = false in the variable section
	 */
	protected $hasModel = true;




	/**
	 *
	 * If the users module is enabled,
	 * and this flag is set to true,
	 * the page controller will be promoted to an admin controller.
	 *
	 * Only users with status of 'admin' are allowed to access it,
	 * without having to include special checks in every function.
	 *
	 * Non-admin users will see a 404 not found if they try to access an
	 * admin controller
	 */
	protected static $admin_area = false;



	/* ***************************************************** CONSTRUCTOR ***************************************************** */

	public function __construct()
	{
		parent::__construct();
	}


	public function __desctruct()
	{
		parent::__destruct();
	}



	/* ***************************************************** CONTROLLER SETTER ***************************************************** */

	/**
	 *
	 * Assign the class and function of the layout
	 * controller to use
	 *
	 * @param string	$class		(name of class)
	 * @param string	$method		(name of function)
	 * @param mixed[]	$params		Function parameter to append to layout function
	 */
	protected function layout($class, $method, $params = array())
	{
		$this->layout = array($class, $method, $params);
	}

	/**
	 *
	 * Use this function to set multiple views
	 *
	 * @param string $key		Key to refer to the view
	 * @param string $view		Name of the view
	 */
	protected function views($key, $view)
	{
		$this->views[$key] = $view;
	}



	/* ***************************************************** CONTROLLER GETTER ***************************************************** */



	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Get multiple Page Controller Views
	 *
	 * @return	mixed[]	$view	array of view names
	 */
	public function getViews()
	{
		return $this->views;
	}


	public function isPlugin()
	{
		return $this->plugin;
	}


	public static function isAuthorized()
	{
		// Only check for authorization if the user module is active.
		// If it is not active, always authorize positively
		if ( $GLOBALS['USER_ENABLE'] == true )
		{
			// NOTE: Using <late static binding> call, to get the
			//		 childs value (overwritten value) rather than my own.
			if ( static::$admin_area && !\Sweany\Users::isAdmin() )
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		// Always authorize if users are not integrated via config.php
		// TODO: add static user to config.php
		else
		{
			return true;
		}
	}


	protected function error($error = '404', $send_headers = false, $options = array())
	{
		// Set core section of language xml
		$this->core->language->setCore('notFound');

		// VIEW VARIABLES
		$this->set('language', $this->core->language);

		// This is a core page (which changes the view path)
		$this->isCore = true;

		// Choose the view depending on the error
		switch ($error)
		{
			// TODO: Also set headers accordingly
			// TODO: read new url from $options (controller/method/params or URI)
			case '301'	:	$this->view('301'); break;	// Moved Permanently.
			case '307'	:	$this->view('307'); break;	// Temporary Redirect. In this case, the request should be repeated with another URI; however, future requests should still use the original URI
			case '308'	:	$this->view('308'); break;	// Permanent Redirect. The request, and all future requests should be repeated using another URI

			// TODO: Also set headers accordingly
			case '403'	:	$this->view('403'); break;	// Forbidden
			case '404'	:	$this->view('404'); break;	// Not Found
			case '410'	:	$this->view('410'); break;	// Gone! No longer avail and won't be avail in the future. Useful to tell Search Engines to not visit again
			case '415'	:	$this->view('415'); break;	// Unsupported Media Type! For example, the client uploads an image as image/svg+xml, but the server requires that images use a different format.
			default		:	$this->view('404'); break;
		}
	}


	/* ***************************************************** REDIRECTS ***************************************************** */

	protected function refresh()
	{
		$this->redirect(\Sweany\Url::getController(), \Sweany\Url::getMethod(), \Sweany\Url::getParams());
	}

	/**
	 * Instant/transparent redirects via php-header().
	 * Note: in Debug Mode you will not be redirected instantly, but rather find a link to click
	 */


	/**
	 *
	 * Redirect to a different page by Ctl/Method
	 * Make sure to encode the parameter values nicely
	 *
	 * @param string $controller (optional)
	 * 		controller Name or null for default controller
	 * @param string $method (optional)
	 * 		method Name or null for default method
	 * @param array $params (optional)
	 * 		params array
	 */
	protected function redirect($controller = null, $method = null, $params = array(), $anchor = null)
	{
		$link = Html::href($controller, $method, $params,$anchor);
		// if debug is on, do not redirect, but show the link instead
		if ( \Sweany\Settings::$showFwErrors )
		{
			echo '<font color="red">Redirect Call: </font><a href="'.$link.'">'.$link.'</a>';
			\Sweany\SysLog::show();
			exit();
		}
		else
		{
			header('Location: '.$link);
			exit();
		}
	}

	/**
	 *
	 * Redirect to front page
	 */
	protected function redirectHome()
	{
		// no parameter leads to null-values and therefore to '/' redirect
		$this->redirect();
	}




	/* *************************************************  D E L A Y E D   R E D I R E C T S ************************************************* */

	/**
	 * The Delayed redirects will present the user with an info page containing a headline and
	 * a body text, giving him/her some information. The info page can be customized under:
	 *
	 * usr/pages/view/FrameworkDefault/info_message.tpl.php
	 *
	 * There also exists an html/javascript redirect after X (specified) seconds and optionally
	 * a clickable link to redirect instantly.
	 */


	/**
	 *
	 * Redirect to the previous page you came from (on this site).
	 *
	 * All internal pages (such as not_found, info, robots) and pages without a view (e.g.: ajax requests)
	 * do not count and will not be stored in the prevPage.
	 */
	protected function redirectBack()
	{
		$prevPage	= \Sweany\History::getPrevPage();
		$controller = $prevPage['controller'];
		$method		= $prevPage['method'];
		$params		= $prevPage['params'];
		$this->redirect($controller, $method, $params);
	}


	/**
	 *
	 * Redirect to the specified page.
	 * Delay the redirect by the amount of seconds specified and display an info message.
	 *
	 * @param string $controller (optional)
	 * 		controller Name or null for default controller
	 * @param string $method (optional)
	 * 		method Name or null for default method
	 * @param array $params (optional)
	 * 		params array
	 * @param string $title
	 * 		the title to display on the info page
	 * @param string $body
	 * 		the body text to display on the info page
	 * @param integer $delay
	 * 		the delay before redirect in seconds
	 */
	protected function redirectDelayed($controller = null, $method = null, $params = array(), $title, $body, $delay = 5)
	{
		$link = Html::href($controller, $method, $params);

		$info['url']	= $link;
		$info['delay']	= $delay;
		$info['title']	= $title;
		$info['body']	= $body;

		\Sweany\Session::set(array(\Sweany\Settings::sessSweany => \Sweany\Settings::sessInfo), $info);

		$this->redirect($GLOBALS['DEFAULT_INFO_MESSAGE_URL']);
	}


	/**
	 *
	 * Redirect to the front page.
	 * Delay the redirect by the amount of seconds specified and display an info message.
	 *
	 * @param string $title
	 * 		the title to display on the info page
	 * @param string $body
	 * 		the body text to display on the info page
	 * @param integer $delay
	 * 		the delay before redirect in seconds
	 */
	protected function redirectDelayedHome($title, $body, $delay = 5)
	{
		$this->redirectDelayed(null, null, null, $title, $body, $delay);
	}


	/**
	 *
	 * Redirect to the previous page you came from (on this site).
	 * Delay the redirect by the amount of seconds specified and display an info message.
	 *
	 * All internal pages (such as not_found, info, robots) and pages without a view (e.g.: ajax requests)
	 * do not count and will not be stored in the prevPage.
	 *
	 * @param string $title
	 * 		the title to display on the info page
	 * @param string $body
	 * 		the body text to display on the info page
	 * @param integer $delay
	 * 		the delay before redirect in seconds
	 */
	protected function redirectDelayedBack($title, $body, $delay = 5)
	{
		$prevPage	= \Sweany\History::getPrevPage();
		$controller = $prevPage['controller'];
		$method		= $prevPage['method'];
		$params		= $prevPage['params'];
		$this->redirectDelayed($controller, $method, $params, $title, $body, $delay);
	}
}
