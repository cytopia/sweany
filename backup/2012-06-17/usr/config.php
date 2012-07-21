<?php
/****************************** PACKAGES **********************/

$PROJECT_DEBUG_LEVEL			= 1;
$LOG_TO_FILE					= 0;
$LOG_FILE						= 'debug.txt';
$DEFAULT_TIME_ZONE				= 'Europe/Berlin';


/************************ DEFAULT CONTROLLER *****************/
// must be in app/FE
$DEFAULT_CONTROLLER				= 'Site';
$DEFAULT_METHOD					= 'index';

// must be in app/FE
$ERROR_CONTROLLER				= 'Site';
$ERROR_METHOD					= 'error';

$REDIRECT_CONTROLLER			= 'Site';
$REDIRECT_METHOD				= 'success_info';


// TODO: remove
/*
$DEFAULT_USER_LOGIN_CTL			= 'User';
$DEFAULT_USER_LOGIN_METHOD		= 'login';

$DEFAULT_USER_LOGOUT_CTL		= 'User';
$DEFAULT_USER_LOGOUT_METHOD		= 'logout';

$DEFAULT_USER_REGISTER_CTL		= 'User';
$DEFAULT_USER_REGISTER_METHOD	= 'login';

$DEFAULT_PROFILE_SHOW_CTL		= 'Profile';
$DEFAULT_PROFILE_SHOW_METHOD	= 'show';

$DEFAULT_MESSAGE_INBOX_CTL		= 'Nachrichten';
$DEFAULT_MESSAGE_INBOX_METHOD	= 'inbox';

$DEFAULT_MESSAGE_WRITE_CTL		= 'Nachrichten';
$DEFAULT_MESSAGE_WRITE_METHOD	= 'write';
*/




// Specify the default css styles to use
// for either the error text or the
// form elements
$DEFAULT_FORM_TEXT_ERR_CSS		= 'color:red;';
$DEFAULT_FORM_ELEMENT_ERR_CSS	= 'border: solid 2px red;';


// Default HTML Defines
$HTML_DEFAULT_LAYOUT			= 'template.tpl.php';
$HTML_DEFAULT_LANG_SHORT		= 'en';
$HTML_DEFAULT_LANG_LONG			= 'en-US';
$HTML_DEFAULT_PAGE_TITLE		= 'the-wire Framework';
$HTML_DEFAULT_PAGE_KEYWORDS		= 'the-wire framework, the-wire, framework';
$HTML_DEFAULT_PAGE_DESCRIPTION	= 'the-wire framework is a MVCTB (Model, View, Controller, Table, Blocks) like framework. It combines the power of drupal with the simplicity of cakephp and on top it is damn fast';


// Database Defines
$SQL_HOST						= '127.0.0.13';
$SQL_DB							= 'framework';
$SQL_USER						= 'root';
$SQL_PASS						= 'thisistherootmysqladmin';

// Database Logging Defines
$SQL_LOG_VISITORS				= 1;




// Password Salt:
// encrypting passwords is done via encryption
// method and a salt. Just put some random
// data here.
// At least 10 Chars is required
// NOTE:
// -------------------------------------------
// DO NOT CHANGE THIS AFTER HAVING SET ONCE,
// OTHERWISE USERS WILL NOT BE ABLE TO LOG BACK IN
$MY_PWD_SALT					= '*&^%$GHJULBJHU*(trfd^%ybJuhiyg78f5V^yb897&^';
?>
