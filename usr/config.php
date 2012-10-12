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
 * @package		sweany.usr
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.9 2012-09-16 13:25
 *
 * User Configuration File
 *
 * This file is called during bootstrap process and will adjust the framework
 * according to the below listed settings.
 */



/***************************************************************************
 *
 *  Runtime Mode
 *
 ***************************************************************************/

  
/**
 *	Runtime Mode
 *
 *	This setting determines the core behavior of Sweany.
 *	You can choose from 1 of 4 possible modes.
 *
 *	@param	integer
 *
 *	Possible Values:
 *		SWEANY_DEVELOPMENT
 *		SWEANY_PRODUCTION
 *		SWEANY_PRODUCTION_FAST_CORE
 *		SWEANY_PRODUCTION_DAEMON
 *
 *	SWEANY_DEVELOPMENT:
 *	-----------------------------------------------------------------------
 *	  You should use this mode until you have fully developed
 *	  and thoroughly tested your application.
 *
 *	  The following options can be turned on in this mode: 
 *		$VALIDATION_MODE		can be used	(runtime validator)
 *		$SHOW_PHP_ERRORS		can be used
 *		$SHOW_SQL_ERRORS		can be used
 *		$SHOW_FRAMEWORK_ERRORS	can be used
 *		$LOG_PHP_ERRORS			can be used
 *		$LOG_SQL_ERRORS			can be used
 *		$LOG_FRAMEWORK_ERRORS	can be used
 *		$BREAK_ON_ERROR			can be used
 *		$DEBUG_CSS				can be used
 *
 *	  The following function behaviour is available
 *	    debug($var)				will output $var to screen
 *
 *
 *	SWEANY_PRODUCTION:
 *	-----------------------------------------------------------------------
 *	  Once you have developed your application and THOROUGHLY tested it
 *	  you can switch to this mode.
 *
 *	  Keep in mind, that all runtime-error checking on user-defined code is removed
 *	  in order to speed things up drastically.
 *		+ no file_exists checks
 *		+ no function exists checks
 *		+ no db connection checks
 *		+ etc...
 *
 *	  The following options will be auto turned off 
 *		$VALIDATION_MODE		off
 *		$SHOW_PHP_ERRORS		off
 *		$SHOW_SQL_ERRORS		off
 *		$SHOW_FRAMEWORK_ERRORS	off
 *		$BREAK_ON_ERROR			off
 *		$DEBUG_CSS				off
 *
 *	  The following function behaviour is available
 *	    debug($var)				will NOT output anything to screen
 *
 *
 *	SWEANY_PRODUCTION_FAST_CORE (TODO: reactivate fast-core mode):
 *  -----------------------------------------------------------------------
 *	  This behaves just like SWEANY_PRODUCTION
 *	  The only difference is, that in this mode,
 *	  the framework only uses a single compressed file in order
 *	  to save disk loading times. This will improve overall performance
 *
 *
 *	SWEANY_PRODUCTION_DAEMON (TODO: not yet implemented):
 *  -----------------------------------------------------------------------
 *	  This behaves just like SWEANY_PRODUCTION
 *	  The only difference is, that you will have to launch the sweany daemon,
 *	  that will pre-load all files into memory.
 *	  Once the daemon is running, a page itself do not have to go through the whole
 *	  bootstrap procedure, as the daemon will take care of this.
 *
 *	  This should be the fastest mode, however, you need to be able to launch the
 *	  daemon from the shell and this is not allowed an all hosters.
 *	  If you cannot launch the daemon, use SWEANY_PRODUCTION_FAST_CORE.
 *
 */
$RUNTIME_MODE = SWEANY_DEVELOPMENT;




/***************************************************************************
 *
 *  SWEANY_DEVELOPMENT Mode Settings
 *
 ***************************************************************************/

/*
 * $VALIDATION_MODE
 *
 * Values:
 * 0: Off
 * 1: On
 *
 * If enabled, the framework will validate various configurations
 * such as existance of configuration flags and their corresponding valid values,
 * readability/writeability and existance of files and various others.
 *
 * See sweany/core/init/Validator.php for details.
 *
 * This however is a performance overhead, so only enable during development
 * and disable for production.
 *
 * Note:
 * If you do any changes or run the framework for the first time, please do
 * turn on this mode.
 *
 */
$VALIDATION_MODE		= 1;


/*
 * Show Errors during runtime
 *
 * Values:
 * 0: Off
 * 1: Error
 * 2: Error & Warning
 * 3: Error & Warning & Notice (Queries for SQL)
 */
$SHOW_PHP_ERRORS		= 3;	// Errors produced by php (such as syntax errors)
$SHOW_FRAMEWORK_ERRORS	= 3;	// Framework errors (such as missing views)
$SHOW_SQL_ERRORS		= 3;	// sql errors (wrong queries... etc)


/*
 * Log Errors to a file
 *
 * Values:
 * 0: Off
 * 1: Error
 * 2: Error & Warning
 * 3: Error & Warning & Notice (Queries for SQL)
 */
$LOG_PHP_ERRORS			= 2;
$LOG_FRAMEWORK_ERRORS	= 2;
$LOG_SQL_ERRORS			= 2;


/*
 * Break on Errors during runtime
 *
 * If an error is caught, it will
 * break the execution of the current page and flush
 * all SysLog messages to the screen.
 *
 * This is recommended if you are in development stage
 * as you could might miss some errors at the bottom of SysLog easily.
 *
 */
$BREAK_ON_ERROR			= 1;


/*
 * Debug Css
 *
 * Colorize all div's on the page
 *
 * Values:
 * 0: Off
 * 1: On
 */
$DEBUG_CSS				= 0;




/***************************************************************************
 *
 *  Log File Paths
 *
 ***************************************************************************/

/*
 * Log Files
 *
 * Define the names of the logfile in ./sweany/log/
 * These files must be writable by the webserver user or group.
 *
 * Enable $VALIDATION_MODE to check propper settings (existance|writeability) for these files.
 */
$FILE_LOG_CORE			= 'core.log';	// Core Logfile (for SysLog)
$FILE_LOG_USER			= 'debug.log';	// User Logfile (for LogCat)






/***************************************************************************
 *
 *  Localization
 *
 ***************************************************************************/

/*
 * $DEFAULT_TIME_ZONE
 *
 * Used for date/time functions to have a local default
 *
 */
$DEFAULT_TIME_ZONE				= 'Europe/Berlin';


/**
 * Default locale for PHP to have date/time functions
 * work accordingly.
 *
 * If you are using the Language Module, then this value will be overriden.
 * Make sure to specify the correct Locale in your xml files.
 */
$DEFAULT_LOCALE					= 'en_US.UTF-8';





/***************************************************************************
 *
*  Default URL Defines
*
***************************************************************************/

/*
 * TODO: not yet implemented completely
 *
$CUSTOM_ROUTING = array(
	'Site'	=> array(
		'controller' => 'Home',
 		'methods'	 => array(
 			'test'		=> 'teswt2',
 		),
 	),
 );
*/




/*
 * $DEFAULT_CONTROLLER
 *
 * When calling http://yourdomain.tld of this project, which controller and method
 * should handle this request?
 *
 * This class must exist in usr/pages/controller
 * And the class must have the corresponding public function
 *
 */
$DEFAULT_CONTROLLER				= 'Home';
$DEFAULT_METHOD					= 'index';


/*
 * $ANY_CONTROLLER_DEFAULT_METHOD
 *
 * Url calls have the following format:
 *
 * http://yourdomain.tld/<ControllerName>/<functionName>/<param1>/<param2>/...
 * If only the controller was specified in the url... such as
 * http://yourdomain.tld/<ControllerName>
 *
 * Then you can specify a function (which must exist in all Controllers)
 * that will handle this request.
 *
 * This not only makes your url calls look nicer, but also let them appear
 * like a tree-organized webpage which is good for seo.
 *
 * Default is: 'index'
 */
$ANY_CONTROLLER_DEFAULT_METHOD	= 'index';




/*
 * $DEFAULT_INFO_MESSAGE_URL
 *
 * This specifies the url placeholder that is displayed when doing an informational redirect.
 * An informational redirect will display a message to the user and then redirect him
 * to a choosen location.
 * As an example, this could be used after logging out, to thank the user and tell him
 * to visit soon.
 *
 * Usage inside a Page Controller:
 *
 * From a controller you can call
 * $this->redirectDelayed(...)
 * $this->redirectDelayedHome(...)
 * $this->redirectDelayedBack(...)
 *
 * These function require a headline and body text for displaying and then
 * redirect the user to the desired location.
 *
 * There is also a view that can be altered in:
 * usr/pages/view/FrameworkDefault/info_message.tpl.php
 *
 * The Place to display the message will be
 * (If DEFAULT_INFO_MESSAGE_URL	= 'note';)
 *
 * http://yourdomain.tld/note
 *
 * This only works via the controller functions. If you enter the url manually you will
 * be redirected to the home page (DEFAULT_CONTROLLER | DEFAULT_METHOD)
 *
 * Note: Make sure not to name any controller like this
 *
 */
$DEFAULT_INFO_MESSAGE_URL		= 'note';


/*
 * $DEFAULT_SETTINGS_URL
 *
 * This one has the same url principle as $DEFAULT_INFO_MESSAGE_URL above
 *
 * But, Instead for info messages while redirecting, this will be used to set settings,
 * which require a reload of the page and eventually a different location as the current one.
 *
 * Note: Also make sure not to name any controller like this.
 *
 *
 */
$DEFAULT_SETTINGS_URL			= 'settings';

/*
 * $DEFAULT_LAYOUT
 *
 * @param	mixed[]|string
 *
 * This is the default Layout to use for all Pages that do not
 * explicitly specify one.
 *
 * You can either set the name of the layout view,
 * that must be present in usr/layouts/view
 *		e.g.:	$DEFAULT_LAYOUT	= 'default.tpl.php'
 * Or you can specify a default layout controller and method
 * that must exist in usr/layouts
 *		e.g.:	$DEFAULT_LAYOUT = array('<LAYOUT_CTRL_NAME>' => '<FUNC_NAME>');
 *
 * The first case bypassed an extra file load (no controller is loaded),
 * only the view file itself.
 *
 * If you need however to attach blocks or set custom variables to your layout,
 * you will have to use a Layout controller to do so.
 *
 * Keep in mind that you can override this in every single controller function.
 */
// TODO: add the controller-> method option to the validator!!!!!!!!1
$DEFAULT_LAYOUT					= 'default.tpl.php';





/***************************************************************************
 *
 *  Email Settings
 *
 ***************************************************************************/

$EMAIL_SYSTEM_FROM_NAME			= 'Your Name';
$EMAIL_SYSTEM_FROM_ADDRESS		= 'noreply@yourdomain.tld';
$EMAIL_SYSTEM_REPLY_ADDRESS		= 'info@yourdomain.tld';
$EMAIL_SYSTEM_RETURN_EMAIL		= 'info@yourdomain.tld';

/*
 * You can turn off sending during development stage.
 * In order to simulate sending you can use the following flag
 * 'EMAIL_STORE_SEND_MESSAGES = 1'
 */
$EMAIL_DO_NOT_SEND				= 1;	// values: 0|1

/*
 * Additionally stores all emails in database.
 * Requires SQL_ENABLE
 */
$EMAIL_STORE_SEND_MESSAGES		= 1;	// values: 0|1




/***************************************************************************
 *
 *  HTML Skeleton Defines
 *
 ***************************************************************************/

/*
 * The following values will be taken as default for every
 * Page that has not explicitly defined its own settings via
 * the HtmlTemplate helper
 */
$HTML_DEFAULT_SKELETON			= 'default.tpl.php';		// dir: usr/skeletons/
$HTML_DEFAULT_LANG_SHORT		= 'en';
$HTML_DEFAULT_LANG_LONG			= 'en-US';
$HTML_DEFAULT_PAGE_TITLE		= 'sweany php';
$HTML_DEFAULT_PAGE_KEYWORDS		= 'sweany mvc php framework, sweany php, sweany, php framework';
$HTML_DEFAULT_PAGE_DESCRIPTION	= 'sweany php is a performance-orientated and programmer-friendly MVCTB (Model, View, Controller, Table, Blocks) framework. It features some functionality from cakephp and drupals block system';





/***************************************************************************
 *
 *  Default Form (on error) Behavior
 *
 ***************************************************************************/

/*
 * When you validate forms via the framework's internal form-validator
 * Then the following css values will be used to display that
 * an input has not been filled in correctly.
 *
 * The text will usually be red and the form element will
 * have a red border indicating which element (such as input box)
 * threw the error.
 *
 * Default:
 * $DEFAULT_FORM_TEXT_ERR_CSS		= 'color:red;';
 * $DEFAULT_FORM_ELEMENT_ERR_CSS	= 'border: solid 2px red;';
 */
$DEFAULT_FORM_TEXT_ERR_CSS		= 'color:red;';
$DEFAULT_FORM_ELEMENT_ERR_CSS	= 'border: solid 2px red;';





/***************************************************************************
*
*  USE included technologies
*
***************************************************************************/

/**
 * Enable/Disable ECSS (Extended CSS)
 * Allows to have inheritance and variables inside CSS files.
 * @link: https://github.com/lockdoc/ecss
 *
 * Note: This only works, when including CSS files via
 * Css::addFile(); Helper
 */
$ECSS_ENABLE		= true;

/**
 * Do you want compressed output
 * @link: https://github.com/lockdoc/ecss/blob/master/README
 * Values: 0 | 1
 */
$ECSS_COMPRESSED	= 0;

/**
 * Do you want commented output
 * @link: https://github.com/lockdoc/ecss/blob/master/README
 * Values: 0 | 1
 */
$ECSS_COMMENTED		= 1;





/***************************************************************************
 *
 *  Enable/Disable and Setup Core Module
 *
 ***************************************************************************/


/**
 * Language
 *
 * @requires: no other module to be enabled
 */
$LANGUAGE_ENABLE				= true;
$LANGUAGE_DEFAULT_SHORT			= 'en';					// Default language (will also override HTML tag language values)
$LANGUAGE_DEFAULT_LONG			= 'en-US';

$LANGUAGE_AVAILABLE				= array('en' => 'English', 'de' => 'Deutsch');
$LANGUAGE_IMG_PATH				= '/img/site/flags';	// need pics available with the following names of 'en.png', 'de.png', ...


/**
 *
 *	SQL Language
 *
 *	Database language translations via
 *	t() function.
 *
 *	@requires: SQL
 *
 *	TODO: implement t() function language
 */
$LANGUAGE_SQL_ENABLE			= true;




/**
 *	SQL
 *
 *	@requires: no other module to be enabled
 *
 *	@param	engines: 'mysql'	(postgres|sqlite is in progress)
 *
 *
 */
$SQL_ENABLE						= true;
$SQL_ENGINE						= 'mysql';				// Currently only 'mysql' is supported
$SQL_HOST						= '127.0.0.13';
$SQL_DB							= 'sweany';
$SQL_USER						= 'root';
$SQL_PASS						= 'thisistherootmysqladmin';


/**
 * User
 *
 * @requires: MySQL
 *
 */
$USER_ENABLE					= true;
$USER_STORE_FAILED_LOGINS		= 1;	// TODO: implement

/**
 * User Online Count
 *
 * @requires: MySQL, User
 */
$USER_ONLINE_COUNT_ENABLE		= true;
$USER_ONLINE_SINCE_MINUTES		= 10;	// count all users since last 10 minutes
$USER_ONLINE_ADD_FAKE_GUESTS	= 10;	// add 10 fake guests to the online count


/**
 * Log Visitors into MySQL DB
 *
 * @requires: MySQL
 */
$SQL_LOG_VISITORS_ENABLE		= true;

// End of File
