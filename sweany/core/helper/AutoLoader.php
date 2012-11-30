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
 * @version		0.8 2012-07-29 13:25
 *
 *
 * This class holds 4 functions.
 *
 * + php overridden 'autoloader'
 * + LoadTable
 * + LoadModel
 * + LoadModule
 */
namespace Sweany;
Class AutoLoader
{

	private static $classes = array();
	private static $configs	= array();



	/***************************************************** LOADER *****************************************************/

	private static function loadPluginConfig($plugin)
	{
		if ( isset(self::$configs[$plugin]) )
		{
			if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
			{
				SysLog::i('user', 'Plugin Config', '['.$plugin.'] config.php already loaded');
			}
			return;
		}
		else
		{
			if ( is_file(USR_PLUGINS_PATH.DS.$plugin.DS.'config.php') )
			{
				require(USR_PLUGINS_PATH.DS.$plugin.DS.'config.php');
				self::$configs[$plugin] = true;

				if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
				{
					SysLog::i('user', 'Plugin Config', '['.$plugin.'] loading config.php');
				}
				return;
			}
			else
			{
				// Store it anyway for performance reasons.
				// Plugin might not have a config, so we store it as true which will avoid to check for
				// the file every time this function is called.
				// (which could be a lot if several blocks of that plugin will be rendered to a single page)
				self::$configs[$plugin] = true;

				if ( Settings::$showFwErrors > 1 || Settings::$logFwErrors > 1 )
				{
					SysLog::w('internal', 'Plugin Config', '['.$plugin.'] config.php does not exist.');
				}
				return;
			}
		}
	}

	public static function loadBlock($class, $plugin)
	{
		$type = '';

		if ( $plugin )
		{
			$type = 'Plugin';
			self::loadPluginConfig($plugin);
		}

		if ( ($return = self::__loadFast($class.'Block', $type.'Block', 'user')) ) {
			return $return;
		}

		$path = ($plugin) ? USR_PLUGINS_PATH.DS.$plugin.DS.'blocks'.DS.$class.'Block.php' : USR_BLOCKS_PATH.DS.$class.DS.$class.'Block.php';

		return self::__loadSlow($class.'Block', array($path), 'Block', 'user');
	}



	public static function loadTable($class, $log_section = 'internal')
	{
		$table	= $class.'Table';

		// Try the fast way
		if ( ($return = self::__loadFast($table, 'Table', $log_section)) ) {
			return $return;
		}

		// no success? Try the slow way
		$paths[] = USR_TABLES_PATH.DS.$table.'.php';

		return self::__loadSlow($table, $paths, 'Table', $log_section);
	}

	public static function loadCoreTable($class, $log_section = 'internal')
	{
		$table	= $class.'Table';

		// Try the fast way
		if ( ($return = self::__loadFast($table, 'Table', $log_section)) ) {
			return $return;
		}

		// no success? Try the slow way
		$paths[] = CORE_TABLE.DS.$table.'.php';

		return self::__loadSlow($table, $paths, 'Table', $log_section);
	}


	public static function loadPluginTable($class, $plugin, $log_section = 'internal')
	{
		$table	= $class.'Table';

		// Try the fast way
		if ( ($return = self::__loadFast($table, 'PluginTable', $log_section)) ) {
			return $return;
		}

		// no success? Try the slow way
		$paths[] = USR_PLUGINS_PATH.DS.$plugin.DS.'tables'.DS.$table.'.php';

		return self::__loadSlow($table, $paths, 'PluginTable', $log_section);
	}

	public static function loadModel($class, $plugin = false)
	{
		$model	= $class.'Model';
		$type	= ($plugin) ? 'Plugin' : '';

		if ($plugin)
		{
			self::loadPluginConfig($plugin);
		}

		// Try the fast way
		if ( ($return = self::__loadFast($model, $type.'Model', 'user')) ) {
			return $return;
		}

		// no success? Try the slow way
		if ( $plugin ) {
			$paths[] = USR_PLUGINS_PATH.DS.$class.DS.'pages'.DS.'model'.DS.$model.'.php';
		} else {
			$paths[] = PAGES_MODEL_PATH.DS.$model.'.php';
		}

		return self::__loadSlow($model, $paths, $type.'Model', 'user');
	}





    /***************************************************** CUSTOM AUTO-LOADER *****************************************************/

	public static function autoload($sClassName)
	{
		$start = microtime(true);

		if (class_exists($sClassName))
		{
			if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
			{
				SysLog::i('internal', 'Auto-Loader', '(already loaded): <strong><font color="blue">' . $sClassName . '</font></strong>', null, null, $start);
			}
			return;
		}

		$ext		= '.php';

		// add Controller
		$paths[]	= PAGES_CONTROLLER_PATH.DS.$sClassName.$ext;

		// Add plugin Controller
		$paths[]	= USR_PLUGINS_PATH.DS.$sClassName.DS.'pages'.DS.'controller'.DS.$sClassName.$ext;

		// Add internal library classes
		$paths[]	= LIB_PATH.DS.$sClassName.$ext;
		$paths[]	= USR_VENDORS_PATH.DS.$sClassName.$ext;
		$paths[]	= LIB_PATH.DS.'lib'.DS.$sClassName.$ext;



		$size = sizeof($paths);

		for($i=0; $i<$size; $i++)
		{
			if ( is_file($paths[$i]) )
			{
				if ( strpos($paths[$i], USR_PLUGINS_PATH) !== false )
				{
					// In case the page refers to a plugin, we first have to load the plugin config
					self::loadPluginConfig($sClassName);
				}

				include($paths[$i]);

				if (class_exists($sClassName, false))
				{
					if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
					{
						SysLog::i('internal', 'Auto-Loader', '(Round '.($i+1).'/'.($size+1).'):<br/><strong><font color="blue">' . $sClassName . '</font></strong> from ' . $paths[$i], null, null, $start);
					}
					return;
				}
				else
				{
					if ( Settings::$showFwErrors > 1 || Settings::$logFwErrors > 1 )
					{
						SysLog::w('internal', 'Auto-Loader', '(Round '.($i+1).'/'.($size+1).'):<br/><strong><font color="#FF6903">' . $sClassName . '</font></strong> not found in ' . $paths[$i], null, null, $start);
					}
					return;
				}
			}
		}

		if ( Settings::$showFwErrors > 1 || Settings::$logFwErrors > 1 )
		{
			SysLog::w('internal', 'Auto-Loader', 'Class not found <strong><font color="red">' . $sClassName . '</font></strong> in all paths:<br/>'.implode($paths, '<br/>'), null, null, $start);
		}
    }




	/***************************************************** PRIVATE FUNCTIONS *****************************************************/
	private static function __loadFast($class, $type, $log_section = 'internal')
	{
	 	$start = microtime(true);

		// 01) Check if class has already been declared
		//     improves speed drastically if having files declaring a single table multiple times
		if ( array_key_exists($class, self::$classes) )
		{
			if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
			{
				SysLog::i($log_section, 'load'.$type, '(Fast If: 1/2):<br/><font color="#FF6903"> '.$class . '</font> already declared, passing reference', null, null, $start);
			}

			return self::$classes[$class];
		}

		// 02) Check if the class has already been loaded into memory
		//     (but we do not have it cataloged, due to autoloader did not tell us)
		//		Also set warning, so we might clean this problem later
		else if ( class_exists($class, false) )
		{
			if ( Settings::$showFwErrors > 1 || Settings::$logFwErrors > 1 )
			{
				SysLog::w($log_section, 'load'.$type, '(Fast If: 2/2):<br/><font color="purple"> '.$class . '</font> already in Memory, but have to redeclare', null, null, $start);
			}

			$c = new $class;

			// store reference to prevent re-declaration
			self::$classes[$class] = &$c;
			return $c;
		}
		return null;
	}



	private static function __loadSlow($class, $paths = array(), $type, $log_section = 'internal')
	{
		$start	= microtime(true);
		$size	= sizeof($paths);

		for ($i=0; $i<$size; $i++)
		{
			// If not declared yet, create instance
			if ( is_file($paths[$i]) )
			{
				// TODO: get rid of _once
				include_once($paths[$i]);

				// TODO: class_exists check really needed???
				if ( class_exists($class, false) )
				{
					if ( Settings::$showFwErrors > 2 || Settings::$logFwErrors > 2 )
					{
						SysLog::i($log_section, 'load'.$type, '(Slow: Round '.($i+1).'/'.($size+1).'):<br/><font color="purple"> '.$class . '</font> in ' . $paths[$i], null, null, $start);
					}
					$c = new $class;

					// store reference to prevent re-declaration
					self::$classes[$class] = &$c;
					return $c;
				}
				else
				{
					SysLog::e($log_section, 'load'.$type, 'No such Class <font color="red">'.$class.'</font> in '.$paths[$i], null, null, $start);
					// Do not exit here, SysLog has its own settings
					//SysLog::show();
					//exit;
					return null;
				}
			}
		}

		// Throw error, as nothing has been found
		SysLog::e($log_section, 'load'.$type, 'No such file <font color="red"><ul>'.implode('<li>',$paths).'</ul></font>', null, null, $start);
		// Do not exit here, SysLog has its own settings
		//SysLog::show();
		//exit;
		return null;
	}

}
spl_autoload_register(array('\Sweany\AutoLoader', 'autoload'));
