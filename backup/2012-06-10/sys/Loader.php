<?php
/**
 *
 * This class holds 4 functions.
 *
 * + php overridden 'autoloader'
 * + LoadTable
 * + LoadModel
 * + LoadModule
 *
 *
 * @author pantu
 *
 */
Class Loader
{

	private static $classes = array();



	/***************************************************** LOADER *****************************************************/

	public static function loadHelper($class)
	{
		if ( ($return = self::__loadFast($class, 'Helper')) )
			return $return;

		$path	= HELPER.DS.$class.'.php';
		return self::__loadSlow($class, array($path), 'Helper');
	}

	public static function loadBlock($class, $package = null)
	{

		if ( ($return = self::__loadFast($class, 'Block')) )
			return $return;

		// 02. If call specifies a package name, then handle it first, as it will be found there
		$path		= ($package) ? PACKAGES.DS.$package.DS.'block'.DS.$class.'Block.php' : BLOCK.DS.$class.DS.$class.'Block.php';

		return self::__loadSlow($class.'Block', array($path), 'Block');
	}



	public static function loadTable($class, $package = null)
	{
		$class = $class.'Table';

		if ( ($return = self::__loadFast($class, 'Table')) )
			return $return;


		// 02. If call specifies a package name, then handle it first, as it will be found there
		$paths		= ($package) ? array(PACKAGES.DS.$package.DS.'tables'.DS.$class.'.php') : array();

		// 03. Add normal tables search path
		$paths[]	= TABLE.DS.$class.'.php';

		// 04 Add all other package tables search paths
		$pkg_size	= sizeof($GLOBALS['USE_PACKAGES']);

		for ($i=0; $i<$pkg_size; $i++)
			$paths[] = PACKAGES.DS.$GLOBALS['USE_PACKAGES'][$i].DS.'tables'.DS.$class.'.php';

		return self::__loadSlow($class, $paths, 'Table');
	}

	public static function loadModel($class, $package = null)
	{
		$class = $class.'Model';

		if ( ($return = self::__loadFast($class, 'Model')) )
			return $return;


		// 02. If call specifies a package name, then handle it first, as it will be found there
		$paths		= ($package) ? array(PACKAGES.DS.$package.DS.'model'.DS.$class.'.php') : array();

		// 03. Add normal model search path
		$paths[]	= MODEL.DS.$class.'.php';

		// 04 Add all other package tables search paths
		$pkg_size	= sizeof($GLOBALS['USE_PACKAGES']);

		for ($i=0; $i<$pkg_size; $i++)
			$paths[] = PACKAGES.DS.$GLOBALS['USE_PACKAGES'][$i].DS.'model'.DS.$class.'.php';

		return self::__loadSlow($class, $paths, 'Model');
	}

	public static function loadModule($class)
	{
		$class = $class.'Module';

		if ( ($return = self::__loadFast($class, 'Module')) )
			return $return;

		$path	= MODULE.DS.$class.'.php';

		return self::__loadSlow($class, array($path), 'Model');
	}





    /***************************************************** CUSTOM AUTO-LOADER *****************************************************/

	public static function autoload($sClassName)
	{
		if (Settings::$debugLevel)
			$start = getmicrotime();

		if (class_exists($sClassName))
		{
			Log::setInfo('Auto-Loader', '(already loaded): <strong><font color="blue">' . $sClassName . '</font></strong>', null, $start);
			return;
		}

		$ext		= '.php';

		// Load Modules, Tables and Controllers
		$paths[]	= CONTROLLER.DS.$sClassName.$ext;
		$paths[]	= CUSTOM.DS.$sClassName.$ext;

		// Load helper classes
//		$paths[]	= HELPER.DS.$sClassName.$ext;
		$paths[]	= LIB.DS.$sClassName.$ext;
		$paths[]	= HL.DS.$sClassName.$ext;

		// Add packages Path
		foreach ( $GLOBALS['USE_PACKAGES'] as $package)
		{
			$paths[]	= PACKAGES.DS.$package.DS.'controller'.DS.$sClassName.$ext;
		}
		$size = sizeof($paths);

		for($i=0; $i<$size; $i++)
		{
			if ( is_file($paths[$i]) )
			{
				include($paths[$i]);

				if (class_exists($sClassName, false))
				{
					Log::setInfo('Auto-Loader', '(Round '.($i+1).'/'.($size+1).'): <strong><font color="blue">' . $sClassName . '</font></strong> from ' . $paths[$i], null, $start);
					return;
				}
//				else if (class_exists($sClassName.'Model', false))
//				{
//					Log::setInfo('Auto-Loader', '(Round '.($i+1).'/'.($size+1).'): <strong><font color="blue">' . $sClassName . 'Model</font></strong> from ' . $paths[$i]);
//					return;
//				}
				else
				{
					Log::setInfo('Auto-Loader', '(Round '.($i+1).'/'.($size+1).'): <strong><font color="#FF6903">' . $sClassName . '</font></strong> not found in ' . $paths[$i], null, $start);
					return;
				}
			}
		}
		Log::setError('Auto-Loader', 'Class not found <strong><font color="red">' . $sClassName . '</font></strong> in all paths', debug_backtrace(), $start);
    }




    /***************************************************** PRIVATE FUNCTIONS *****************************************************/
    private static function __loadFast($class, $type)
    {
    	if (Settings::$debugLevel)
    		$start = getmicrotime();

    	// 01) Check if class has already been declared
    	//     improves speed drastically if having files declaring a single table multiple times
    	if ( array_key_exists($class, self::$classes) )
    	{
    		Log::setInfo('load'.$type, '(Fast: If: 1/2):<font color="#FF6903"> '.$class . '</font> already declared, passing reference', null, $start);
    		return self::$classes[$class];
    	}

    	// 02) Check if the class has already been loaded into memory
    	//     (but we do not have it cataloged, due to autoloader did not tell us)
    	//		Also set warning, so we might clean this problem later
    	else if ( class_exists($class, false) )
    	{
    		Log::setWarn('load'.$type, '(Fast: If: 2/2):<font color="purple"> '.$class . '</font> already in Memory, but have to redeclare ', null, $start);

    		$c = new $class;

    		// store reference to prevent re-declaration
    		self::$classes[$class] = &$c;
    		return $c;
    	}
    	return null;
    }
    private static function __loadSlow($class, $paths = array(), $type)
    {
    	if (Settings::$debugLevel)
    		$start = getmicrotime();

    	$size	= sizeof($paths);

    	for ($i=0; $i<$size; $i++)
    	{
    		// If not declared yet, create instance
    		if (file_exists($paths[$i]))
    		{
    			// TODO: get rid of _once
    			include_once($paths[$i]);

    			// TODO: class_exists check really needed???
    			if ( class_exists($class, false) )
    			{
    				Log::setInfo('load'.$type, '(Slow: Round '.($i+1).'/'.($size+1).'):<font color="purple"> '.$class . '</font> in ' . $paths[$i], null, $start);

    				$c = new $class;

    				// store reference to prevent re-declaration
    				self::$classes[$class] = &$c;
    				return $c;
    			}
    			else
    			{
    				Log::setError('load'.$type, 'No such Class <font color="red">'.$class.'</font> in '.$paths[$i], debug_backtrace(), $start);
    				return null;
				}
			}
		}

		// Throw error, as nothing has been found
		Log::setError('load'.$type, 'No such file <font color="red"><ul>'.implode('<li>',$paths).'</ul></font>', debug_backtrace(), $start);
		return null;
    }
}

spl_autoload_register(array('Loader', 'autoload'));

?>