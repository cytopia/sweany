<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 cytopia.
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
 * @copyright	Copyright 2011-2012, cytopia
 * @link		none yet
 * @package		sweany.core
 * @author		cytopia <cytopia@everythingcli.org>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.9 2012-08-17 13:25
 *
 * Renderer
 */
namespace Sweany;
class Render
{
	/**
	 *
	 * Creates a renderable element from a given block
	 * and returns the return code with the element
	 * @param String $controllerName
	 * @param String $methodName
	 * @param Array $params
	 */
	public static function block($pluginName, $controllerName, $methodName, $params)
	{
		// 01) Load in instantiate block

		$block = \Sweany\AutoLoader::loadBlock($controllerName, $pluginName);

		if ( !method_exists(get_class($block), $methodName) )
		{
			$backtrace	= debug_backtrace();
			$thisTrace	= $backtrace[count($backtrace)-1];
			$classTrace	= isset($thisTrace['class'])	? $thisTrace['class'].'->'	: '';
			$funcTrace	= isset($thisTrace['function'])	? $thisTrace['function']	: '';

			$error		= '<br/><br/>[call] from: '.$classTrace.$funcTrace;
			SysLog::e('user', 'Render Block', '('.get_class($block).') '.$controllerName.'->'.$methodName.'('.implode(',', $params).') does not exist.'.$error);
			SysLog::show();
			exit();
		}

		// 02) set language to correct xml section
		//     so that the block can use it without having
		//     to specify it itself.
		if ( $GLOBALS['LANGUAGE_ENABLE'] )
		{
			$block->core->language->set($methodName);
		}


		/**
		 * 03) execute the block
		 *
		 * This way is around twice as fast as call_user_func_array
		 *
		 */
		$paramSize = count($params);
		switch ( $paramSize )
		{
			case 0:  $ret = $block->{$methodName}();break;
			case 1:  $ret = $block->{$methodName}($params[0]);break;
			case 2:  $ret = $block->{$methodName}($params[0], $params[1]);break;
			case 3:  $ret = $block->{$methodName}($params[0], $params[1], $params[2]);break;
			case 4:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3]);break;
			case 5:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4]);break;
			case 6:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]);break;
			case 7:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]);break;
			case 8:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);break;
			case 9:  $ret = $block->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);break;
			default: $ret = call_user_func_array(array($block, $methodName), $params); break;
		}
		if ( $ret === false )
		{
			SysLog::e('user', 'Render Block', '[Call] '.get_class($block).'->'.$methodName.'('.implode(',', $params).') returns FALSE');
			SysLog::show();
			exit();
		}


		/**
		 * This is the important part to determine whether the block itself will
		 * hold a non renderable result, such as an ajax request.
		 *
		 * If $this->render is set to false
		 * The return value of the block will also be used as the output to show.
		 * Not rendering only means, the block does not want to use a view (save some performance xD)
		 */
		if ( !$block->render )
		{
			return array('ret' => $ret, 'render' => false, 'html' => $ret);
		}


		// 04) ------- set view variables
		foreach ($bVars	= $block->getVars() as $name => $value)
		{
			$$name = $value;
		}
		// 05 ------- Set Blocks (defined by controller) - yes Blocks can also attach other blocks!
		foreach ($bBlocks = $block->getBlocks() as $name => $value)
		{
			$$name = $value;
		}


		// 06) get View
		$view		= $block->getView().'.tpl.php';
		$view_path	= strlen($pluginName) ? USR_PLUGINS_PATH.DS.$pluginName.DS.'blocks'.DS.'view'.DS.$view : USR_BLOCKS_PATH.DS.$controllerName.DS.'view'.DS.$view;

		if (!is_file($view_path))
		{
			SysLog::e('user', 'Render Block', 'Block View: '.$view_path. ' does not exist');
			$view_path = CORE_VIEW.DS.'missing.tpl.php';	// use error view
		}

		// 07) RENDER
		if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
		{
			ob_start(\Sweany\Settings::$ob_callback);
		}
		else
		{
			ob_start();
		}

		include($view_path);

		$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();

		// 08) Clean (erase) the output buffer and turn off output buffering
		ob_end_clean();

		// 09 Highlight Block for admins if desired
		if ( Users::isAdmin() )
		{
			$highlight = \Sweany\Session::get(Settings::sessSweany, Settings::sessAdmin);

			if ( isset($highlight['blocks']) && $highlight['blocks'] == 'highlight' )
			{
				$name = ($pluginName) ? $pluginName.'::' : '';
				$name.= $controllerName.'->'.$methodName;
				// TODO: important!! Link on syslog (if user is admin, to enable block showing via settings controller)!!!
				$container = '<div style="border: solid 3px red;margin:-3px;z-index:100; position:relative;">';
				$container.=	'<div style="background:red; color:white; position:absolute;margin:-3px;padding:3px;">';
				$container.=	'<strong>'.$name.'</strong><br/>'.basename($view_path);
				$container.=	'</div>';
				$container.=	'%s';
				$container.= '</div>';

				$content = sprintf($container, $content);
			}
		}



		// 09 Restore Header
		//
		// In case the block Controller did set a custom header e.g,
		// header("Content-Type: image/png"); then it will still be active here
		// so we have to restore it
		// TODO:
		//header('Content-type: text/html; charset=UTF-8');

		return array('ret' => $ret, 'render' => true, 'html' => $content);
	}



	/**
	 *
	 * Renders a PageController View and returns
	 * the rendered element
	 *
	 * @param	&class	$controller
	 * @return	string	$content		Rendered view
	 */
	public static function view(&$controller)
	{
		$class			= get_class($controller);	// the name of the controller class
		$vars			= $controller->getVars();
		$blocks			= $controller->getBlocks();
		$plugin			= $controller->isPlugin();
		$viewName		= $controller->getView().'.tpl.php';


		// Core views for core controller reside in a different place
		if ( $controller->isCore )
		{
			// Core view (such as 404.tpl.php, admin_emails.tpl.php, ...) can be
			// overwritten in usr/pages/_core.
			// If there is a view present in the users place,
			// we will take that one instead of the core one.
			if ( is_file(USR_PAGES_PATH.DS.'_core'.DS.$viewName) )
			{
				$viewPath = USR_PAGES_PATH.DS.'_core'.DS.$viewName;
				SysLog::i('user', 'Render Core View [overwrite]', 'Overwriting Core view, Using: '.$viewPath);
			}
			else
			{
				$viewPath	= CORE_VIEW.DS.'FrameworkDefault'.DS.$viewName;
				SysLog::i('user', 'Render Core View', 'Using: '.$viewPath);
			}
		}
		else
		{
			$viewPath	= ($plugin) ? USR_PLUGINS_PATH.DS.$class.DS.'pages'.DS.'view'.DS.$viewName : PAGES_VIEW_PATH.DS.$class.DS.$viewName;
			SysLog::i('user', 'Render View', 'Using: '.$viewPath);
		}


		// ------- Check if view exists
		if (!is_file($viewPath))
		{
			SysLog::e('user', 'Render View', 'view '.$viewPath. ' does not exist');
			$viewPath = CORE_VIEW.DS.'missing.tpl.php';	// use error view
		}

		// ------- Set Variables (defined by controller)
		foreach ($vars as $name => $var)
		{
			$$name = $var;
		}

		// ------- Set Blocks (defined by controller)
		foreach ($blocks as $name => $block)
		{
			$$name = $block;
		}

		// -------- RENDER
		if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
		{
			ob_start(\Sweany\Settings::$ob_callback);
		}
		else
		{
			ob_start();
		}

		@include($viewPath);
		$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
		ob_end_clean();

		// -------- PLUGIN VIEW WRAPPER
		if ($plugin)
		{
			$content = self::wrapper($plugin, $viewName, $content);
		}

		return $content;
	}

	/**
	 *
	 * Renders multiple PageController Views and returns
	 * the rendered elements
	 *
	 * @param	&class			$controller
	 * @return	string[]|null	$content		An array of rendered view OR null if none available
	 */
	public static function views(&$controller)
	{
		// If there are no multiple views available, return null
		if ( !$viewNames = $controller->getViews() )
		{
			return null;
		}

		$class			= get_class($controller);	// the name of the controller class
		$vars			= $controller->getVars();
		$blocks			= $controller->getBlocks();
		$plugin			= $controller->isPlugin();
		$viewPaths		= array();


		// retrieve view paths
		foreach ($viewNames as $key => $viewName)
		{
			$viewPaths[$key] = ($plugin) ? USR_PLUGINS_PATH.DS.$class.DS.'pages'.DS.'view'.DS.$viewName.'.tpl.php' : PAGES_VIEW_PATH.DS.$class.DS.$viewName.'.tpl.php';
			SysLog::i('user', 'Render Views', 'Using: '.$viewPaths[$key]);


			// ------- Check if views exist
			if (!is_file($viewPaths[$key]))
			{
				SysLog::e('user', 'Render Views', 'view '.$viewPaths[$key]. ' does not exist');
				$viewPaths[$key] = CORE_VIEW.DS.'missing.tpl.php';	// use error view
			}
		}


		// ------- Set Variables (defined by controller)
		foreach ($vars as $name => $var)
		{
			$$name = $var;
		}
		// ------- Set Blocks (defined by controller)
		foreach ($blocks as $name => $block)
		{
			$$name = $block;
		}

		// -------- RENDER
		foreach ($viewPaths as $key => $viewPath)
		{
			if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
			{
				ob_start(\Sweany\Settings::$ob_callback);
			}
			else
			{
				ob_start();
			}

			@include($viewPath);
			$content[$key] = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
			ob_end_clean();

			// -------- PLUGIN VIEW WRAPPER
			if ($plugin)
			{
				$content[$key] = self::wrapper($plugin, $viewNames[$key], $content[$key]);
			}
		}

		return $content;
	}




	/**
	 *
	 * Renders are layout and takes a single view and/or multiple views
	 *
	 * @param	&class			$controller		Controller reference
	 * @param	null|string		$view			One rendered view OR null
	 * @param	null|string[]	$views			Array of rendered views OR null
	 * @return	string			$content		Rendered Layout
	 */
	public static function layout(&$controller, $view = null, $views = null)
	{
		$layout			= $controller->getLayout();

		// Only execute if an actual class/method has been specified
		// Otherwise just take the default layout
		if ( isset($layout[0]) && isset($layout[1]) || is_array($GLOBALS['DEFAULT_LAYOUT']) )
		{
			if ( isset($layout[0]) && isset($layout[1]) )
			{
				$className		= $layout[0];
				$methodName		= $layout[1];
				$params			= $layout[2];
			}
			else
			{
				$className		= $GLOBALS['DEFAULT_LAYOUT'][0];
				$methodName		= $GLOBALS['DEFAULT_LAYOUT'][1];
				$params			= isset($GLOBALS['DEFAULT_LAYOUT'][2]) ? $GLOBALS['DEFAULT_LAYOUT'][2] : array();
			}
			$classPath		= USR_LAYOUTS_PATH.DS.$className.'.php';

			if ( is_file($classPath) )
			{
				include($classPath);

				if ( method_exists($className, $methodName) )
				{
					// create instance
					$layoutCtl = new $className;

					// set language
					if ( $GLOBALS['LANGUAGE_ENABLE'] )
					{
						$layoutCtl->core->language->set($methodName);
					}

					// execute method
					$paramSize = count($params);
					switch ( $paramSize )
					{
						case 0:  $ret = $layoutCtl->{$methodName}();break;
						case 1:  $ret = $layoutCtl->{$methodName}($params[0]);break;
						case 2:  $ret = $layoutCtl->{$methodName}($params[0], $params[1]);break;
						case 3:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2]);break;
						case 4:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3]);break;
						case 5:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4]);break;
						case 6:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5]);break;
						case 7:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6]);break;
						case 8:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7]);break;
						case 9:  $ret = $layoutCtl->{$methodName}($params[0], $params[1], $params[2], $params[3], $params[4], $params[5], $params[6], $params[7], $params[8]);break;
						default: $ret = call_user_func_array(array($layoutCtl, $methodName), $params); break;
					}
					if ( $ret === false )
					{
						SysLog::e('user', 'Render Layout', '[Call] '.get_class($layoutCtl).'->'.$methodName.'('.implode(',', $params).') returns FALSE');
						SysLog::show();
						exit();
					}

					$vars		= $layoutCtl->getVars();
					$blocks		= $layoutCtl->getBlocks();

					// ------- Set Variables (defined by controller)
					foreach ($vars as $name => $var)
					{
						$$name = $var;
					}

					// ------- Set Blocks (defined by controller)
					foreach ($blocks as $name => $block)
					{
						$$name = $block;
					}

					$layoutView	= USR_LAYOUTS_PATH.DS.'view'.DS.$layoutCtl->getView().'.tpl.php';
				}
				else
				{
					SysLog::e('user', 'Render Layout', 'Class or Method does not exist. <'.$className.'> -> <'.$methodName.'>');
					SysLog::show();
					exit;
				}
			}
			else
			{
				SysLog::e('user', 'Render Layout', 'Class File does not exist: '.$classPath);
				SysLog::show();
				exit;
			}
		}
		// Use default layout
		else
		{
			SysLog::i('user', 'Render Layout', 'Not set. Using default');
			$layoutView	= USR_LAYOUTS_PATH.DS.'view'.DS.$GLOBALS['DEFAULT_LAYOUT'];

			/*
			 * As there is no controller for the default layout view,
			 * we have to set a few things manually.
			 * Such as $user and $language (if modules are enabled)
			 */
			if ( $GLOBALS['USER_ENABLE'] )
			{
				$user		= new \Sweany\Users;
			}
			if ( $GLOBALS['LANGUAGE_ENABLE'] )
			{
				$language	= new \Sweany\Language(null, 'layout', 'default');
			}

		}

		//		$render_element = $view;
		SysLog::i('user', 'Render Layout', 'Using Layout View: '.$layoutView);


		// ------- Check if layout exists
		if (!is_file($layoutView))
		{
			SysLog::e('user', 'Render Layout', 'Layout View: '.$layoutView. ' does not exist');
			$layoutView = CORE_VIEW.DS.'missing.tpl.php';	// use error view
		}


		// -------- RENDER
		if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
		{
			ob_start(\Sweany\Settings::$ob_callback);
		}
		else
		{
			ob_start();
		}

		// If you are the admin, show the administration panel, on top of everything
		if ( Users::isAdmin() )
		{
			include(CORE_BUILT_IN.DS.'admin'.DS.'panel.tpl.php');
		}

		include($layoutView);
		$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public static function skeleton($layout)
	{
		if ( is_file(USR_SKELETONS_PATH.DS.'default.tpl.php'/*$GLOBALS['HTML_DEFAULT_SKELETON']*/) )
		{
			// -------- RENDER
			if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
			{
				ob_start(\Sweany\Settings::$ob_callback);
			}
			else
			{
				ob_start();
			}

			include(USR_SKELETONS_PATH.DS.'default.tpl.php'/*$GLOBALS['HTML_DEFAULT_SKELETON']*/);
			$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
			ob_end_clean();
		}
		else
		{
			SysLog::e('user', 'Render Skeleton', 'File does not exist: Class File does not exist: '.USR_SKELETONS_PATH.DS.'default.tpl.php'/*$GLOBALS['HTML_DEFAULT_SKELETON']*/);
			SysLog::show();
			exit;
		}
		return $content;
	}

	public static function email($message, $skeletonPath)
	{
		// ------- Check if email skeleton exists
		if (!is_file($skeletonPath))
		{
			SysLog::e('user', 'Render Email', 'Skeleton View: '.$skeletonPath. ' does not exist');
			SysLog::show();
			exit;
		}

		// -------- RENDER
		if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
		{
			ob_start(\Sweany\Settings::$ob_callback);
		}
		else
		{
			ob_start();
		}
		include($skeletonPath);
		$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
		ob_end_clean();

		return $content;
	}


	/**
	 * Render a plugin view into a wrapper view.
	 * This is used to customize already rendered views from plugins.
	 *
	 * To use a wrapper you will have to create a view file with the same name
	 * as the plugin view to use. See usr/pages/wrapper/README for details
	 *
	 * @param string $pluginName	name of the plugin
	 * @param string $viewName		name of the view of the plugin
	 * @param string $view			rendered view of the plugin
	 * @return string				rendered wrapper view
	 */
	private static function wrapper($pluginName, $viewName, $view)
	{
		/*
		 * TODO: need to set custom language paths here
		 *       and also the $user object
		 */
		/*
		 * TODO: need to determine if the wrapper should have access
		 *       to the variables, the view has
		 */
		if ( is_file(PAGES_WRAPPER_PATH.DS.$pluginName.DS.$viewName) )
		{
			SysLog::i('user', 'Render View', 'Add Wrapper for '.$pluginName.'-plugin: '.$viewName);

			// -------- RENDER
			if ( !in_array(\Sweany\Settings::$ob_callback, ob_list_handlers())  )
			{
				ob_start(\Sweany\Settings::$ob_callback);
			}
			else
			{
				ob_start();
			}
			include(PAGES_WRAPPER_PATH.DS.$pluginName.DS.$viewName);
			$content = $GLOBALS['COMPRESS_HTML'] ? self::compress(ob_get_contents()) : ob_get_contents();
			ob_end_clean();

			// 09 Highlight Wrapper for admins if desired
			if ( Users::isAdmin() )
			{
				$highlight = \Sweany\Session::get(Settings::sessSweany, Settings::sessAdmin);

				if ( isset($highlight['wrapper']) && $highlight['wrapper'] == 'highlight' )
				{
					// TODO: important!! Link on syslog (if user is admin, to enable block showing via settings controller)!!!
					$container = '<div style="border: solid 3px green;margin:-3px;z-index:100; position:relative;">';
					$container.=	'<div style="background:green; color:white; position:absolute;margin:-3px;padding:3px;">';
					$container.=	'<strong>'.$pluginName.' wrapper</strong><br/>'.basename($viewName);
					$container.=	'</div>';
					$container.=	'%s';
					$container.= '</div>';

					$content = sprintf($container, $content);
				}
			}

			return $content;
		}
		else
		{
			SysLog::i('user', 'Render View', 'No Wrapper for '.$pluginName.'-plugin: '.$viewName);
			return $view;
		}
	}

	private static function compress($html)
	{
		return preg_replace('/^\s+|\n|\r|\s+$/m', '', $html);
	}
}
