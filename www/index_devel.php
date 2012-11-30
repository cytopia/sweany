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
 * @package		sweany
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * DEVELOPMENT FRONTEND
 */


define('DS', DIRECTORY_SEPARATOR);

 /* ************************************************************************************************************
 *
 * Framework specific defines
 *
 * ************************************************************************************************************/


define('FRAMEWORK', ROOT.DS.'sweany');							// Path where the framework resides

//---------- Framework root folders
define('CORE_PATH', FRAMEWORK.DS.'core');						// Core Framework Path
define('LOG_PATH', FRAMEWORK.DS.'log');							// Path to Logfile folder
define('LIB_PATH', FRAMEWORK.DS.'helper');						// Path to Libraries users can use

//---------- Core folders
define('CORE_BOOTSTRAP', CORE_PATH.DS.'bootstrap');				// Path to bootstrap folder
define('CORE_CACHE', FRAMEWORK.DS.'cache');						// Path to cache folder
define('CORE_DATABASE', CORE_PATH.DS.'database');				// Path to database folder
define('CORE_HELPER', CORE_PATH.DS.'helper');					// Path to core helper classes (Render, Syslog)
define('CORE_BUILT_IN', CORE_PATH.DS.'built-in');				// Path to built in Controllers, Views and Tables (and their templates)
define('CORE_VALIDATOR', CORE_PATH.DS.'validator');				// Path to framework validators folder
define('CORE_RENDERABLE', CORE_PATH.DS.'renderable');			// Path to framework renderables (json, xml, png, jpeg, ....)


define('CORE_CONTROLLER', CORE_BUILT_IN.DS.'controller');
define('CORE_MODEL', CORE_BUILT_IN.DS.'model');
define('CORE_VIEW', CORE_BUILT_IN.DS.'view');
define('CORE_TABLE', CORE_BUILT_IN.DS.'tables');




/* ************************************************************************************************************
 *
 * Project specific defines
 *
 * ************************************************************************************************************/


define('USR_PATH', ROOT.DS.'usr');								// Project Home Path

//---------- USR folders
define('USR_BLOCKS_PATH', USR_PATH.DS.'blocks');
define('USR_LANGUAGES_PATH', USR_PATH.DS.'languages');
define('USR_LAYOUTS_PATH', USR_PATH.DS.'layouts');
define('USR_PAGES_PATH', USR_PATH.DS.'pages');
define('USR_PLUGINS_PATH', USR_PATH.DS.'plugins');
define('USR_SKELETONS_PATH', CORE_PATH.DS.'skeletons'.DS.'html');
define('USR_MAIL_SKELETON_PATH', CORE_PATH.DS.'skeletons'.DS.'email');
define('USR_TABLES_PATH', USR_PATH.DS.'tables');
define('USR_VENDORS_PATH', USR_PATH.DS.'vendors');

//---------- Pages folders
define('PAGES_CONTROLLER_PATH', USR_PAGES_PATH.DS.'controller');
define('PAGES_MODEL_PATH', USR_PAGES_PATH.DS.'model');
define('PAGES_VIEW_PATH', USR_PAGES_PATH.DS.'view');
define('PAGES_WRAPPER_PATH', USR_PAGES_PATH.DS.'wrapper');



/* ************************************************************************************************************
 *
 * Include Files
 *
 * ************************************************************************************************************/

/*
 * Load Functions and Error Handler
 */
require(CORE_PATH.DS.'functions.php');
require(CORE_HELPER.DS.'ErrorHandler.php');

// Set Error Handler
set_error_handler(array('\Sweany\ErrorHandler', 'php_error_handler'));
set_exception_handler(array('\Sweany\ErrorHandler', 'php_exception_handler'));
register_shutdown_function(array('\Sweany\ErrorHandler', 'php_shutdown_handler'));

/*
 * Load Configuration Store
 */
require(CORE_PATH.DS.'Config.php');

/*
 * Load Bootstrap Initializer
 */
require(CORE_BOOTSTRAP.DS.'aBootTemplate.php');
require(CORE_BOOTSTRAP.DS.'Settings.php');
require(CORE_BOOTSTRAP.DS.'Router.php');
require(CORE_BOOTSTRAP.DS.'Session.php');
require(CORE_BOOTSTRAP.DS.'Language.php');
require(CORE_BOOTSTRAP.DS.'Database.php');
require(CORE_BOOTSTRAP.DS.'Url.php');
require(CORE_BOOTSTRAP.DS.'Users.php');
require(CORE_BOOTSTRAP.DS.'OnlineUsers.php');


/*
 *	Load Framework Core Helper
 */
require(CORE_HELPER.DS.'SysLog.php');
require(CORE_HELPER.DS.'History.php');
require(CORE_HELPER.DS.'Render.php');
require(CORE_HELPER.DS.'AutoLoader.php');

/*
 * Load Framework Structure Template Files
 */
require(CORE_BUILT_IN.DS.'BaseController.php');
require(CORE_BUILT_IN.DS.'LayoutController.php');
require(CORE_BUILT_IN.DS.'BlockController.php');
require(CORE_BUILT_IN.DS.'PageController.php');
require(CORE_BUILT_IN.DS.'PageModel.php');
require(CORE_BUILT_IN.DS.'Table.php');



$FILE_LOAD_TIME = ( microtime(true) - ($_SERVER['REQUEST_TIME']+$SERVER_REACTION_TIME) );


/* ************************************************************************************************************
 *
 * BOOTSTRAP
 *
 * ************************************************************************************************************/


// ----------   1.) Initialize the Settings
if ( !\Sweany\Settings::initialize() )
{
	// we cannot use log here, when settings breaks
	// as the Logging settings are initialized in the settings
	// itself, so we just output the error via 'echo'
	echo \Sweany\Settings::getError();
	exit();
}
\Sweany\SysLog::i('core', 'Files', 'All Framework files loaded', null, $FILE_LOAD_TIME);
\Sweany\SysLog::i('core', 'Settings', 'Settings loaded successfully.');


// ----------   2.) Load the Validator (in case it is not set, load it to produce readable error)
if ( !isset($GLOBALS['VALIDATION_MODE']) || $GLOBALS['VALIDATION_MODE'] == true )
{
	$timeBefore = microtime(true);

	require(CORE_BOOTSTRAP.DS.'Validator.php');
	require(CORE_VALIDATOR.DS.'Validate01Basics.php');
	require(CORE_VALIDATOR.DS.'Validate02Config.php');
	require(CORE_VALIDATOR.DS.'Validate03Language.php');
	require(CORE_VALIDATOR.DS.'Validate04Database.php');
	require(CORE_VALIDATOR.DS.'Validate05Tables.php');
	require(CORE_VALIDATOR.DS.'Validate06User.php');
	require(CORE_VALIDATOR.DS.'Validate07UserOnlineCount.php');
	require(CORE_VALIDATOR.DS.'Validate08LogVisitors.php');
	require(CORE_VALIDATOR.DS.'Validate09Plugins.php');

	// ----------   2.1) Initialize the Validator
	if ( !\Sweany\Validator::initialize() )
	{
		\Sweany\SysLog::e('core', 'Validation', \Sweany\Validator::getError());
		\Sweany\SysLog::show();
		exit();
	}
	\Sweany\SysLog::i('core', 'Validation', 'Validation OK.', null, microtime(true)-$timeBefore);
}


// ----------   2.) Initialize the Session
if ( !\Sweany\Session::initialize() )
{
	\Sweany\SysLog::e('core', 'Session', \Sweany\Session::getError());
	\Sweany\SysLog::show();
	exit();
}
\Sweany\SysLog::i('core', 'Session', 'Session Initialized successfully');


// ----------   3.) Initialize the Url
if ( !\Sweany\Url::initialize() )
{
	\Sweany\SysLog::e('core', 'Url', \Sweany\Url::getError());
	\Sweany\SysLog::show();
	exit();
}
\Sweany\SysLog::i('core', 'URL', 'Url loaded successfully, Request: '.\Sweany\Url::$request);



// ----------   5.) Initialize MySQL
if ( $GLOBALS['SQL_ENABLE'] == true )
{
	if ( !\Sweany\Database::initialize() )
	{
		\Sweany\SysLog::e('core-module', 'Database', \Sweany\Database::getError());
		\Sweany\SysLog::show();
		exit();
	}
	\Sweany\SysLog::i('core-module', 'Database', 'Database ('.$GLOBALS['SQL_ENGINE'].') Initialized successfully, using db: '.$GLOBALS['SQL_DB']);


	// ----------   6.) Initialize Users
	if ( $GLOBALS['USER_ENABLE'] == true )
	{
		if ( !\Sweany\Users::initialize() )
		{

			\Sweany\SysLog::e('core-module', 'Users', \Sweany\Users::getError());
			\Sweany\SysLog::show();
			exit();
		}
		\Sweany\SysLog::i('core-module', 'Users', 'Users Initialized successfully, current user: (id: '.\Sweany\Users::id().') '.\Sweany\Users::name());

		if ( !\Sweany\OnlineUsers::initialize() )
		{

			\Sweany\SysLog::e('core-module', 'Online Users', \Sweany\OnlineUsers::getError());
			\Sweany\SysLog::show();
			exit();
		}
		\Sweany\SysLog::i('core-module', 'OnlineUsers', 'Online Users Initialized successfully');
	}

	// ----------   7.) Post Settings
	// Log visitors to SQL
	if ( $GLOBALS['SQL_LOG_VISITORS_ENABLE'] == true )
	{
		$logger = \Sweany\AutoLoader::loadCoreTable('Visitors');
		$logger->save(null);
		\Sweany\SysLog::i('core-module', 'Visitors', 'Logging Page Visitor');
	}
}

// ----------   4.) Initialize the Language
if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
{
	if ( !\Sweany\Language::initialize() )
	{
		\Sweany\SysLog::e('core-module', 'Language', \Sweany\Language::getError());
		\Sweany\SysLog::show();
		exit();
	}
	\Sweany\SysLog::i('core-module', 'Language', 'Language Initialized successfully. Using: '.\Sweany\Language::getLangShort());
}


// ----------   8.) Initialize the Framework Router
if ( !\Sweany\Router::initialize() )
{
	\Sweany\SysLog::e('core', 'Router', \Sweany\Router::getError());
	\Sweany\SysLog::show();
	exit();
}
\Sweany\SysLog::i('core', 'Router', 'Router Initialized successfully');


$BOOTSTRAP_TIME = ( microtime(true) - ($_SERVER['REQUEST_TIME']+$SERVER_REACTION_TIME+$FILE_LOAD_TIME) );
\Sweany\SysLog::i('core', 'BOOTSTRAP', 'Bootstrap finished successfully', null, $BOOTSTRAP_TIME);



/* ************************************************************************************************************
 *
 * CALL
 *
 * ************************************************************************************************************/


/****************************************** Controller Call ******************************************/


$object	= \Sweany\Router::getObject();

$class	= $object['class'];
$method	= $object['method'];
$params	= $object['params'];

$c		= new $class;


\Sweany\SysLog::i('user', '-- CALL --', 'Calling Controller <strong><font color="green">'.$class.'->'.$method.'("'.implode('", "',$params).'")</font></strong>');
$CALL_START_TIME = microtime(true);

// set language
if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
{
	$c->core->language->set($method);
}

/**
 * faster than call_user_func_array
 */
$paramSize = count($params);

\Sweany\SysLog::time('core', microtime(true)-$SCRIPT_START_TIME);	// now everything by sweany has been done!
switch ( $paramSize )
{
	case 0:  $result = $c->{$method}();break;
	case 1:  $result = $c->{$method}($params[0]);break;
	case 2:  $result = $c->{$method}($params[0], $params[1]);break;
	case 3:  $result = $c->{$method}($params[0], $params[1], $params[2]);break;
	case 4:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3]);break;
	case 5:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3], $params[4]);break;
	case 6:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]);break;
	case 7:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]);break;
	case 8:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);break;
	case 9:  $result = $c->{$method}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);break;
	default: $result = call_user_func_array(array($c, $method), $params); break;
}


$CALL_END_TIME = microtime(true) - $CALL_START_TIME;
\Sweany\SysLog::i('user', '-- CALL --', 'Controller Call finished successfully', null, $CALL_END_TIME);



/****************************************** RENDER CASE l ******************************************/

//
// CASE 1:
// if render is false, we just want to output the return
// of the function. Used for ajax requests to get a value
//
if ( !$c->render )
{
	echo $result;

	// Cleanup
	\Sweany\Settings::cleanup();
	\Sweany\Url::cleanup();
	\Sweany\Router::cleanup();
	\Sweany\Session::cleanup();
	if ( $GLOBALS['SQL_ENABLE'] ) {\Sweany\Database::cleanup();}
	exit();
}


/****************************************** RENDER CASE 2 ******************************************/

//
// CASE 2:
// if render is true, get the view,
// put it into the layout and render it to the browser
//
else
{
	/*
	 * If the router decided that it is a normal
	 * visitable page, then we will add it to the history
	 * tracker in order to be able to go back to this one.
	 */
	if ( \Sweany\Router::$visitablePage )
	{
		\Sweany\History::track();
	}

	/*
	 * If it is a renderable page with html code,
	 * we will need to check if CSS debugging should be applied
	 */
	if ( $GLOBALS['DEBUG_CSS'] )
	{
		Javascript::addFile('/sweany/debug.js');
		Javascript::setOnPageLoadFunction('debugDiv()');
	}


	// ------ RENDER VIEW
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ){ $start = microtime(true);}
	$view = \Sweany\Render::view($c);
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ){ \Sweany\SysLog::i('user', 'Render View', 'Total Execution Time', null, null, $start);}

	// ------ RENDER ADDITIONAL MULTIPLE VIEWS
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ){ $start = microtime(true); }
	$views = \Sweany\Render::views($c);
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ){ \Sweany\SysLog::i('user', 'Render Views', 'Total Execution Time', null, null, $start);}

	// ------ RENDER LAYOUT
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ) {$start = microtime(true);}
	$layout	= \Sweany\Render::layout($c, $view, $views);
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ) { \Sweany\SysLog::i('user', 'Render Layout', 'Total Execution Time', null, null, $start);}

	// ------ RENDER SKELETON
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ) {$start = microtime(true);}
	$skeleton	= \Sweany\Render::skeleton($layout);
	if ( \Sweany\Settings::$showFwErrors > 2 || \Sweany\Settings::$logFwErrors > 2 ) { \Sweany\SysLog::i('user', 'Render Skeleton', 'Total Execution Time', null, null, $start);}

	// ------ OUTPUT TO SCREEN
	echo $skeleton;


	// ------ CLEANUP
	\Sweany\Settings::cleanup();
	\Sweany\Url::cleanup();
	\Sweany\Router::cleanup();
	\Sweany\Session::cleanup();
	if ( $GLOBALS['SQL_ENABLE'] ) {\Sweany\Database::cleanup();}


	// ------ LOGGIN
	\Sweany\SysLog::time('total', microtime(true)-$SCRIPT_START_TIME);
	\Sweany\SysLog::show();
	exit();
}
