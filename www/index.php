<?php
/* Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 * Frontend for handling everything */
$SCRIPT_START_TIME=microtime(true);
$SERVER_REACTION_TIME=($SCRIPT_START_TIME-$_SERVER['REQUEST_TIME']);
define('ROOT',(dirname(dirname(__FILE__))));
define('SWEANY_DEVELOPMENT',0x1);
define('SWEANY_PRODUCTION',0x2);
define('SWEANY_PRODUCTION_FAST_CORE',0x3);
define('SWEANY_PRODUCTION_DAEMON',0x4);
require(ROOT.DIRECTORY_SEPARATOR.'usr'.DIRECTORY_SEPARATOR.'config.php');
if($GLOBALS['RUNTIME_MODE']==SWEANY_DEVELOPMENT || $GLOBALS['RUNTIME_MODE']==SWEANY_PRODUCTION){require(ROOT.DIRECTORY_SEPARATOR.'www'.DIRECTORY_SEPARATOR.'index_development.php');exit();}
/* FAST CORE MODE STARTS HERE */
else{
define('DS',DIRECTORY_SEPARATOR);
define('FRAMEWORK',ROOT.DS.'sweany');
define('CORE_PATH',FRAMEWORK.DS.'core');
define('LOG_PATH',FRAMEWORK.DS.'log');
define('LIB_PATH',FRAMEWORK.DS.'lib');
define('CORE_BOOTSTRAP',CORE_PATH.DS.'bootstrap');
define('CORE_CACHE',CORE_PATH.DS.'cache');
define('CORE_DATABASE',CORE_PATH.DS.'database');
define('CORE_HELPER',CORE_PATH.DS.'helper');
define('CORE_PAGES',CORE_PATH.DS.'pages');
define('CORE_STRUCTURE',CORE_PATH.DS.'structure');
define('CORE_VALIDATOR',CORE_PATH.DS.'validator');
define('CORE_VIEWS',CORE_PATH.DS.'views');
define('LIB_HL_PATH',LIB_PATH.DS.'highlighter');
define('USR_PATH',ROOT.DS.'usr');
define('USR_BLOCKS_PATH',USR_PATH.DS.'blocks');
define('USR_LANGUAGES_PATH',USR_PATH.DS.'languages');
define('USR_LAYOUTS_PATH',USR_PATH.DS.'layouts');
define('USR_PAGES_PATH',USR_PATH.DS.'pages');
define('USR_PLUGINS_PATH',USR_PATH.DS.'plugins');
define('USR_SKELETONS_PATH',USR_PATH.DS.'skeletons'.DS.'html');
define('USR_MAIL_SKELETON_PATH',USR_PATH.DS.'skeletons'.DS.'email');
define('USR_TABLES_PATH',USR_PATH.DS.'tables');
define('USR_VENDORS_PATH',USR_PATH.DS.'vendors');
define('PAGES_CONTROLLER_PATH',USR_PAGES_PATH.DS.'controller');
define('PAGES_MODEL_PATH',USR_PAGES_PATH.DS.'model');
define('PAGES_VIEW_PATH',USR_PAGES_PATH.DS.'view');
define('PAGES_WRAPPER_PATH',USR_PAGES_PATH.DS.'wrapper');
require(FRAMEWORK.DS.'core.php');
Sweany\Settings::initialize();
Sweany\Session::initialize();
Sweany\Url::initialize();
Sweany\Router::initialize();
if($GLOBALS['LANGUAGE_ENABLE']==true){Sweany\Language::initialize();}
if($GLOBALS['SQL_ENABLE']==true){
	Sweany\Database::initialize();
	if($GLOBALS['USER_ENABLE']==true){Sweany\Users::initialize();}
	if($GLOBALS['SQL_LOG_VISITORS_ENABLE']==true){$logger=Loader::loadTable('Visitors');$logger->add();}
}
$object=\Sweany\Router::getObject();
$class=$object['class'];
$method=$object['method'];
$params=$object['params'];
$c=new $class;
if($GLOBALS['LANGUAGE_ENABLE']==true){$c->language->set($method);}
$paramSize=count($params);
switch($paramSize){
	case 0:$result=$c->{$method}();break;
	case 1:$result=$c->{$method}($params[0]);break;
	case 2:$result=$c->{$method}($params[0],$params[1]);break;
	case 3:$result=$c->{$method}($params[0],$params[1],$params[2]);break;
	case 4:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3]);break;
	case 5:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3],$params[4]);break;
	case 6:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3],$params[4],$params[5]);break;
	case 7:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6]);break;
	case 8:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6],$params[7]);break;
	case 9:$result=$c->{$method}($params[0],$params[1],$params[2],$params[3],$params[4],$params[5],$params[6],$params[7],$params[8]);break;
	default: $result=call_user_func_array(array($c,$method),$params);break;
}
if(!$c->render){/*RENDER CASE l*/
	echo $result;
	\Sweany\Settings::cleanup();
	\Sweany\Url::cleanup();
	\Sweany\Router::cleanup();
	\Sweany\Session::cleanup();
	if($GLOBALS['SQL_ENABLE']){\Sweany\Database::cleanup();}
}else{/*RENDER CASE 2*/
	if(\Sweany\Router::$visitablePage){\Sweany\History::track();}
	$view=\Sweany\Render::view($c);
	$views=\Sweany\Render::views($c);
	$layout=\Sweany\Render::layout($c,$view,$views);
	$skeleton=\Sweany\Render::skeleton($layout);
	echo $skeleton;
	\Sweany\Settings::cleanup();
	\Sweany\Url::cleanup();
	\Sweany\Router::cleanup();
	\Sweany\Session::cleanup();
	if($GLOBALS['SQL_ENABLE']){\Sweany\Database::cleanup();}
}
exit();
}