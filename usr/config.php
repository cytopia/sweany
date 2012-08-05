<?php
/**
 * User Configuration File
 *
 * This file is called during bootstrap process and will adjust the framework
 * according to the below listed settings.
 *
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
 * @package		sweany.usr
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.8 2012-08-05 13:25
 */



/***************************************************************************
 *
 *  Runtime Modes
 *
 ***************************************************************************/

/*
 * $FAST_CORE_MODE
 *
 * Values:
 * 0: Off
 * 1: On
 *
 * If this mode is enabled the framework will use only one file
 * that has a compressed version of the whole core code.
 * This speeds up disk loading times drastically, as only a single
 * file has to be read from disk.
 *
 * Important Note:
 * ----------------------------------------------------------------
 * -
 * -  The FAST_CORE_MODE does not have core 'notice/info'
 * -  logging facilities neither is the validation mode implemented
 * -  This mode only displays/logs (if enabled below) php/sql/framework
 * -  errors and warnings.
 * -  Time measuring of core execution is removed as well.
 * -  Only use in production or you might miss something.
 * -
 * ----------------------------------------------------------------
 *
 * TODO: not implemented yet.
 * Will be available once sweany reaches release candidate state.
 *
 */
$FAST_CORE_MODE			= 0;

 
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



/***************************************************************************
 *
 *  DEBUGGING
 *
 ***************************************************************************/

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
 * Log Errors to a file (see below for which file)
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
 * Log Files
 *
 * Define the names of the logfile in ./sweany/log/
 * These files must be writable by the webserver user or group.
 *
 * Enable $VALIDATION_MODE to check propper settings for these files.
 *
 *
 * TODO: The user logger still needs to be implemented
 * as a helper to write to $FILE_LOG_USER 
 * Coming name: LogCat.php
 */
$FILE_LOG_CORE			= 'core.log';	// logs all the above stuff (if enabled)
$FILE_LOG_USER			= 'debug.log';	// For custom project logging




/***************************************************************************
 *
 *  Default Defines
 *
 ***************************************************************************/

/*
 * $DEFAULT_TIME_ZONE
 *
 * Used for date/time functions to have a local default
 *
 */
$DEFAULT_TIME_ZONE				= 'Europe/Berlin';


/*
 * $DEFAULT_CONTROLLER
 *
 * When calling http://yourdomain.tld of this project controller and method
 * should handle this request
 *
 * This class must exist in usr/pages/controller
 * And the class must have the corresponding public function
 *
 */ 
$DEFAULT_CONTROLLER				= 'Site';
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
 * Default is: 'index'
 */
$ANY_CONTROLLER_DEFAULT_METHOD	= 'index';



/*
 * $DEFAULT_LAYOUT
 *
 * If you do not use or do not need a custom Layout Controller,
 * then you need to tell the framework which default layout-view you want to use
 *
 * It must be in usr/layouts/view/
 * Default is: 'default.tpl.php'
 *
 */
$DEFAULT_LAYOUT					= 'default.tpl.php';


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
 * TODO: Still lacks implementation
 *
 */
$DEFAULT_SETTINGS_URL			= 'settings';




/***************************************************************************
 *
 *  Email Settings
 *
 ***************************************************************************/

$EMAIL_SYSTEM_FROM_NAME			= 'Your Name';
$EMAIL_SYSTEM_FROM_ADDRESS		= 'noreply@yourdomain.tld';
$EMAIL_SYSTEM_REPLY_ADDRESS		= 'info@yourdomain.tld';
$EMAIL_SYSTEM_RETURN_EMAIL		= 'info@yourdomain.tld';




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
 *  Enable/Disable and Setup Core Module
 *
 ***************************************************************************/

/**
 * TODO: right now, you cannot deactivate any of the core
 * modules. This still needs to be implemented.
 */



/**
 * Language
 *
 *
 * @requires: no other module to be enabled
 */
$LANGUAGE_ENABLE				= true;
$LANGUAGE_DEFAULT_SHORT			= 'en';					// Default language (will also override HTML tag language values)
$LANGUAGE_DEFAULT_LONG			= 'en-US';

$LANGUAGE_AVAILABLE				= array('en' => 'English', 'de' => 'Deutsch');
$LANGUAGE_IMG_PATH				= '/img/site/flags';	// need pics in the form of en.png, de.png, ...


/**
 * MySQL
 *
 * @requires: no other module to be enabled
 */
$SQL_ENABLE						= true;
$SQL_HOST						= '127.0.0.13';
$SQL_DB							= 'sweany';
$SQL_USER						= 'root';
$SQL_PASS						= 'thisistherootmysqladmin';


/**
 * User
 *
 * @requires: MySQL
 *
 * Note:
 * sweany ships with a few default users in the database.
 * If you change the salt, you will als have to change the
 * password hashes in the databse.
 *
 * Minimum required Length: 10 characters!
 * 
 */
$USER_ENABLE					= true;
$USER_PWD_SALT					= '*&^%$GHJULBJHU*(trfd^%ybJuhiyg78f5V^yb897&^';


/**
 * User Online Count
 *
 * @requires: MySQL, User
 */
$USER_ONLINE_COUNT_ENABLE		= true;


/**
 * Log Visitors into MySQL DB
 *
 * @requires: MySQL
 */
$SQL_LOG_VISITORS				= true;
