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
 * Frontend for handling everything
 */

$SERVER_REACTION_TIME = ( microtime(true) - $_SERVER['REQUEST_TIME'] );

/* ************************************************************************************************************
 *
 * Global defines
 *
 * ************************************************************************************************************/

// System independent directory separator
define('DS', DIRECTORY_SEPARATOR);

// Root Path
define('ROOT', (dirname(dirname(__FILE__))));	// parent directory




 /* ************************************************************************************************************
 *
 * Framework specific defines
 *
 * ************************************************************************************************************/


define('FRAMEWORK', ROOT.DS.'sweany');							// Path where the framework resides

//---------- Framework root folders
define('CORE_PATH', FRAMEWORK.DS.'core');						// Core Framework Path
define('LOG_PATH', FRAMEWORK.DS.'log');							// Path to Logfile folder
define('LIB_PATH', FRAMEWORK.DS.'lib');							// Path to Libraries users can use

//---------- Core folders
define('CORE_INIT_PATH', CORE_PATH.DS.'init');					// Path to Initialization files
define('CORE_PAGES_PATH', CORE_PATH.DS.'pages');				// Path to Framework default pages (viewable)
define('CORE_TPL_PATH', CORE_PATH.DS.'templates');				// Path to html skeleton templates
define('CORE_VIEWS_PATH', CORE_PATH.DS.'views');				// Path to view (modes) files (json, xml...)
define('CORE_VALIDATOR_PATH', CORE_PATH.DS.'validator');		// Path to validators

//---------- Lib folders
define('LIB_HL_PATH', LIB_PATH.DS.'highlighter');				// Path to various code highlighters





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
define('USR_SKELETONS_PATH', USR_PATH.DS.'skeletons'.DS.'html');
define('USR_MAIL_SKELETON_PATH', USR_PATH.DS.'skeletons'.DS.'email');
define('USR_TABLES_PATH', USR_PATH.DS.'tables');
define('USR_VENDORS_PATH', USR_PATH.DS.'vendors');

//---------- Pages folders
define('PAGES_CONTROLLER_PATH', USR_PAGES_PATH.DS.'controller');
define('PAGES_MODEL_PATH', USR_PAGES_PATH.DS.'model');
define('PAGES_VIEW_PATH', USR_PAGES_PATH.DS.'view');
define('PAGES_WRAPPER_PATH', USR_PAGES_PATH.DS.'wrapper');






/* ************************************************************************************************************
 *
 * Including Files
 *
 * ************************************************************************************************************/


// Load Configuration
require(USR_PATH.DS.'config.php');

// TODO: fast core disabled, as the file core.php needs to be updated
//if ( $GLOBALS['FAST_CORE_MODE'] == 1 )
//{
//	// Load Basic functions
//	require(FRAMEWORK.DS.'core.php');
//}
//else
//{
	// Load Basic functions
	require(CORE_PATH.DS.'functions.php');
	require(CORE_PATH.DS.'CustomError.php');
	
	// Set Error Handler
	set_error_handler(array('CustomError', 'error_handler'));
	set_exception_handler(array('CustomError', 'exception_handler'));
	register_shutdown_function(array('CustomError', 'shutdown_handler'));

	/*
	 * Load Configuration Store
	 */
	require(CORE_PATH.DS.'Config.php');
	 /*
	 * Load Initializer
	 */
	require(CORE_INIT_PATH.DS.'CoreAbstract.php');
	require(CORE_INIT_PATH.DS.'CoreSettings.php');
	require(CORE_INIT_PATH.DS.'CoreCallback.php');
	require(CORE_INIT_PATH.DS.'CoreSession.php');
	require(CORE_INIT_PATH.DS.'CoreLanguage.php');
	require(CORE_INIT_PATH.DS.'CoreDatabase.php');
	require(CORE_INIT_PATH.DS.'CoreUrl.php');
	require(CORE_INIT_PATH.DS.'CoreUsers.php');

	/*
	 * Load Framework files
	 */
	require(CORE_PATH.DS.'SysLog.php');
	require(CORE_PATH.DS.'History.php');
	require(CORE_PATH.DS.'Loader.php');
	require(CORE_PATH.DS.'Render.php');
	require(CORE_PATH.DS.'BaseController.php');
	require(CORE_PATH.DS.'LayoutController.php');
	require(CORE_PATH.DS.'BlockController.php');
	require(CORE_PATH.DS.'PageController.php');
	require(CORE_PATH.DS.'PageModel.php');
	require(CORE_PATH.DS.'Table.php');
//}



// TODO: outcomment in live version
$FILE_LOAD_TIME = ( microtime(true) - ($_SERVER['REQUEST_TIME']+$SERVER_REACTION_TIME) );


/* ************************************************************************************************************
 *
 * BOOTSTRAP
 *
 * ************************************************************************************************************/


// ----------   1.) Initialize the Settings
if ( !\Core\Init\CoreSettings::initialize() )
{
	// we cannot use log here, when settings breaks
	// as the Logging settings are initialized in the settings
	// itself, so we just output the error via 'echo'
	echo \Core\Init\CoreSettings::getError();
	exit();
}
SysLog::i('-- SERVER --', 'Reaction Time  '.round($SERVER_REACTION_TIME, 4).' seconds');
SysLog::i('-- LOAD --', 'Framework files loaded in '.round($FILE_LOAD_TIME, 4).' seconds');
SysLog::i('Core', 'Settings loaded successfully.');



// ----------   2.) Load the Validator (in case it is not set, load it to produce readable error)
if ( !isset($GLOBALS['VALIDATION_MODE']) || $GLOBALS['VALIDATION_MODE'] == true )
{
	$timeBefore = microtime(true);

	require(CORE_INIT_PATH.DS.'Validator.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate01Basics.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate02Config.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate03Language.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate04Database.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate05User.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate06UserOnlineCount.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate07LogVisitors.php');
	require(CORE_VALIDATOR_PATH.DS.'Validate08Plugins.php');

	// ----------   2.1) Initialize the Validator
	if ( !\Core\Init\Validator::initialize() )
	{
		SysLog::e('Validation', \Core\Init\Validator::getError());
		SysLog::show();
		exit();
	}
	SysLog::i('Core', 'Validation OK. Took '.sprintf('%.6F',microtime(true)-$timeBefore).' sec');
}



// ----------   2.) Initialize the Session
if ( !\Core\Init\CoreSession::initialize() )
{
	SysLog::e('Session', \Core\Init\CoreSession::getError());
	SysLog::show();
	exit();
}
SysLog::i('Core', 'Session Initialized successfully');


// ----------   3.) Initialize the Url
if ( !\Core\Init\CoreUrl::initialize() )
{
	SysLog::e('Url', \Core\Init\CoreUrl::getError());
	SysLog::show();
	exit();
}
SysLog::i('Core', 'Url loaded successfully, Request: '.\Core\Init\CoreUrl::$request);


// ----------   4.) Initialize the Framework Callback
if ( !\Core\Init\CoreCallback::initialize() )
{
	SysLog::e('Callback', \Core\Init\CoreCallback::getError());
	SysLog::show();
	exit();
}
SysLog::i('Core', 'Callback Initialized successfully');


// ----------   5.) Initialize the Language
if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
{
	if ( !\Core\Init\CoreLanguage::initialize() )
	{
		SysLog::e('Language', \Core\Init\CoreLanguage::getError());
		SysLog::show();
		exit();
	}
	SysLog::i('Core', 'Language Initialized successfully. Using: '.\Core\Init\CoreLanguage::getLangShort());
}

// ----------   6.) Initialize MySQL
if ( $GLOBALS['SQL_ENABLE'] == true )
{
	if ( !\Core\Init\CoreDatabase::initialize() )
	{
		SysLog::e('Database', \Core\Init\DatabaseMySql::getError());
		SysLog::show();
		exit();
	}
	SysLog::i('Core', 'Database ('.$GLOBALS['SQL_ENGINE'].') Initialized successfully, using db: '.$GLOBALS['SQL_DB']);


	// ----------   7.) Initialize Users
	if ( $GLOBALS['USER_ENABLE'] == true )
	{
		if ( !\Core\Init\CoreUsers::initialize() )
		{
			SysLog::e('Users', \Core\Init\CoreUsers::getError());
			SysLog::show();
			exit();
		}
		SysLog::i('Core', 'Users Initialized successfully, current user: (id: '.\Core\Init\CoreUsers::id().') '.\Core\Init\CoreUsers::name());
	}
	
	// ----------   8.) Post Settings
	// Log visitors to SQL
	if ( $GLOBALS['SQL_LOG_VISITORS_ENABLE'] == true && $GLOBALS['SQL_ENABLE'] == true )
	{
		$logger = Loader::loadTable('Visitors');
		$logger->add();
	}
	
}

$BOOTSTRAP_TIME = ( microtime(true) - ($_SERVER['REQUEST_TIME']+$SERVER_REACTION_TIME+$FILE_LOAD_TIME) );
SysLog::i('-- BOOTSTRAP --', 'done in '.round($BOOTSTRAP_TIME, 4).' seconds');





/* ************************************************************************************************************
 *
 * CALL
 *
 * ************************************************************************************************************/


/****************************************** Controller Call ******************************************/


$object	= \Core\Init\CoreCallback::getObject();

$class	= $object['class'];
$method	= $object['method'];
$params	= $object['params'];

$c		= new $class;


SysLog::i('-- CALL --', 'calling <strong><font color="green">'.$class.'->'.$method.'("'.implode('", "',$params).'")</font></strong>');
$CALL_START_TIME = microtime(true);

// set language
if ( $GLOBALS['LANGUAGE_ENABLE'] == true )
{
	$c->language->set($method);
}

/**
 * faster than call_user_func_array
 */
$paramSize = count($params);
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
SysLog::i('-- CALL --', 'done in  '.sprintf('%.6F',$CALL_END_TIME).' sec.');





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
	\Core\Init\CoreSettings::cleanup();
	\Core\Init\CoreUrl::cleanup();
	\Core\Init\CoreCallback::cleanup();
	\Core\Init\CoreSession::cleanup();
	if ( $GLOBALS['SQL_ENABLE'] ) {\Core\Init\CoreDatabase::cleanup();}

	// Loggin
	SysLog::i('End', 'Execution finished');
	SysLog::i('Total Page Time', 'loaded in '.round(microtime(true) - $_SERVER['REQUEST_TIME'], 4).' seconds');
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
	 * If the callback decided that it is a normal
	 * visitable page, then we will add it to the history
	 * tracker in order to be able to go back to this one.
	 */
	if ( \Core\Init\CoreCallback::$visitablePage )
	{
		History::track();
	}

	/*
	 * If it is a renderable page with html code,
	 * we will need to check if CSS debugging should be applied
	 */
	if ( $GLOBALS['DEBUG_CSS'] )
	{
		Javascript::addFile('/js/debug.js');
		Javascript::setOnPageLoadFunction('debugDiv()');
	}


	// ------ RENDER VIEW
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ){ $start = microtime(true);}
	$view = Render::view($c);
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ){ SysLog::i('Render View', 'Total Execution Time', null, $start);}

	// ------ RENDER ADDITIONAL MULTIPLE VIEWS
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ){ $start = microtime(true); }
	$views = Render::views($c);
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ){ SysLog::i('Render Views', 'Total Execution Time', null, $start);}

	// ------ RENDER LAYOUT
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ) {$start = microtime(true);}
	$layout	= Render::layout($c, $view, $views);
	if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 ) {SysLog::i('Render Layout', 'Total Execution Time', null, $start);}


	/* This is already validated in validator.php
	 * TODO: if having the option to have several skeletons
	 * then we have to validate here as well.
	// ------ INCLUDE SKELETON
	if (!is_file(HTML_SKELETON_TPL))
	{
		Log::setError('Html Skeleton', 'skeleton '.HTML_SKELETON_TPL. ' does not exist');
		Log::show();
		exit;
	}
	*/

	include(USR_SKELETONS_PATH.DS.$GLOBALS['HTML_DEFAULT_SKELETON']);



	// ------ CLEANUP
	\Core\Init\CoreSettings::cleanup();
	\Core\Init\CoreUrl::cleanup();
	\Core\Init\CoreCallback::cleanup();
	\Core\Init\CoreSession::cleanup();
	if ( $GLOBALS['SQL_ENABLE'] ) {\Core\Init\CoreDatabase::cleanup();}


	// ------ LOGGIN
	SysLog::i('DONE', 'Total Page Time: '.round(microtime(true) - $_SERVER['REQUEST_TIME'], 4).' seconds');
	SysLog::show();
	exit();
}
