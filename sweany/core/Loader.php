<?php
/**
 * This class holds 4 functions.
 *
 * + php overridden 'autoloader'
 * + LoadTable
 * + LoadModel
 * + LoadModule
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
 * @package		sweany.sys
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
Class Loader
{

	private static $classes = array();



	/***************************************************** LOADER *****************************************************/

	/*
	public static function loadHelper($class)
	{
		if ( ($return = self::__loadFast($class, 'Helper')) )
			return $return;

		$path	= HELPER.DS.$class.'.php';
		return self::__loadSlow($class, array($path), 'Helper');
	}
	*/

	public static function loadBlock($class, $plugin)
	{
		$type = ($plugin) ? 'Plugin' : '';

		if ( ($return = self::__loadFast($class.'Block', $type.'Block')) )
			return $return;

		$path = ($plugin) ? USR_PLUGINS_PATH.DS.$plugin.DS.'blocks'.DS.$class.'Block.php' : USR_BLOCKS_PATH.DS.$class.DS.$class.'Block.php';

		return self::__loadSlow($class.'Block', array($path), 'Block');
	}



	public static function loadTable($class)
	{
		$table	= $class.'Table';

		// Try the fast way
		if ( ($return = self::__loadFast($table, 'Table')) )
			return $return;

		// no success? Try the slow way
		$paths[] = USR_TABLES_PATH.DS.$table.'.php';

		return self::__loadSlow($table, $paths, 'Table');
	}

	public static function loadPluginTable($class, $plugin)
	{
		$table	= $class.'Table';


		// Try the fast way
		if ( ($return = self::__loadFast($table, 'PluginTable')) )
			return $return;

		// no success? Try the slow way
		$paths[] = USR_PLUGINS_PATH.DS.$plugin.DS.'tables'.DS.$table.'.php';

		return self::__loadSlow($table, $paths, 'PluginTable');
	}

	public static function loadModel($class, $plugin = false)
	{
		$model	= $class.'Model';
		$type	= ($plugin) ? 'Plugin' : '';

		// Try the fast way
		if ( ($return = self::__loadFast($model, $type.'Model')) )
			return $return;

		// no success? Try the slow way
		if ( $plugin )
			$paths[] = USR_PLUGINS_PATH.DS.$class.DS.'pages'.DS.'model'.DS.$model.'.php';
		else
			$paths[] = PAGES_MODEL_PATH.DS.$model.'.php';

		return self::__loadSlow($model, $paths, $type.'Model');
	}


/*
	public static function loadModule($class)
	{
		$class = $class.'Module';

		if ( ($return = self::__loadFast($class, 'Module')) )
			return $return;

		$path	= MODULE.DS.$class.'.php';

		return self::__loadSlow($class, array($path), 'Model');
	}
*/




    /***************************************************** CUSTOM AUTO-LOADER *****************************************************/

	public static function autoload($sClassName)
	{
		$start = microtime(true);

		if (class_exists($sClassName))
		{
			SysLog::i('Auto-Loader', '(already loaded): <strong><font color="blue">' . $sClassName . '</font></strong>', null, $start);
			return;
		}

		$ext		= '.php';

		// add Controller
		$paths[]	= PAGES_CONTROLLER_PATH.DS.$sClassName.$ext;

		// Add plugin Controller
		$paths[]	= USR_PLUGINS_PATH.DS.$sClassName.DS.'pages'.DS.'controller'.DS.$sClassName.$ext;

		// Add internal library classes
		$paths[]	= LIB_PATH.DS.$sClassName.$ext;
		$paths[]	= LIB_HL_PATH.DS.$sClassName.$ext;



		$size = sizeof($paths);

		for($i=0; $i<$size; $i++)
		{
			if ( is_file($paths[$i]) )
			{
				include($paths[$i]);

				if (class_exists($sClassName, false))
				{
					SysLog::i('Auto-Loader', '(Round '.($i+1).'/'.($size+1).'): <strong><font color="blue">' . $sClassName . '</font></strong> from ' . $paths[$i], null, $start);
					return;
				}
				else
				{
					SysLog::i('Auto-Loader', '(Round '.($i+1).'/'.($size+1).'): <strong><font color="#FF6903">' . $sClassName . '</font></strong> not found in ' . $paths[$i], null, $start);
					return;
				}
			}
		}
		SysLog::w('Auto-Loader', 'Class not found <strong><font color="red">' . $sClassName . '</font></strong> in all paths', debug_backtrace(), $start);
    }




    /***************************************************** PRIVATE FUNCTIONS *****************************************************/
    private static function __loadFast($class, $type)
    {
	 	$start = microtime(true);

    	// 01) Check if class has already been declared
    	//     improves speed drastically if having files declaring a single table multiple times
    	if ( array_key_exists($class, self::$classes) )
    	{
    		SysLog::i('load'.$type, '(Fast: If: 1/2):<font color="#FF6903"> '.$class . '</font> already declared, passing reference', null, $start);
    		return self::$classes[$class];
    	}

    	// 02) Check if the class has already been loaded into memory
    	//     (but we do not have it cataloged, due to autoloader did not tell us)
    	//		Also set warning, so we might clean this problem later
    	else if ( class_exists($class, false) )
    	{
    		SysLog::w('load'.$type, '(Fast: If: 2/2):<font color="purple"> '.$class . '</font> already in Memory, but have to redeclare ', null, $start);

    		$c = new $class;

    		// store reference to prevent re-declaration
    		self::$classes[$class] = &$c;
    		return $c;
    	}
    	return null;
    }
    private static function __loadSlow($class, $paths = array(), $type)
    {
		$start = microtime(true);

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
    				SysLog::i('load'.$type, '(Slow: Round '.($i+1).'/'.($size+1).'):<font color="purple"> '.$class . '</font> in ' . $paths[$i], null, $start);

    				$c = new $class;

    				// store reference to prevent re-declaration
    				self::$classes[$class] = &$c;
    				return $c;
    			}
    			else
    			{
    				SysLog::e('load'.$type, 'No such Class <font color="red">'.$class.'</font> in '.$paths[$i], debug_backtrace(), $start);
    				return null;
				}
			}
		}

		// Throw error, as nothing has been found
		SysLog::e('load'.$type, 'No such file <font color="red"><ul>'.implode('<li>',$paths).'</ul></font>', debug_backtrace(), $start);
		return null;
    }
}

spl_autoload_register(array('Loader', 'autoload'));

?>
