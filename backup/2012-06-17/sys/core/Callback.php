<?PHP
/**
 *
 * This core module will extract the class name, function name
 * and function parameter values from the given URL request
 *
 */
class Callback extends CoreTemplate
{

	private static $object	= array();

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	/**
	 *
	 * Sets the Controller, Function and Params to the $object - depending on the given URL parameter
	 *
	 * Security Checks:		Checks if requested Class/Function is allowed to execute
	 * Error Handling:		asigns Default- or Error Controller/Method
	 *
	 */
	public static function initialize()
	{
		$controller	= Url::getController();
		$method		= Url::getMethod();
		$params		= Url::getParams();

		
		// No controller specified, so start with the default entry point
		if ( !$controller )
		{
			Log::setWarn('Callback', 'no url request made - using default controller');

			require(CONTROLLER.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php');
			require(MODEL.DS.$GLOBALS['DEFAULT_CONTROLLER'].'Model.php');

			self::$object = array(
				'class'		=> $GLOBALS['DEFAULT_CONTROLLER'],
				'method'	=> $GLOBALS['DEFAULT_METHOD'],
				'params'	=> array(),
			);
			return true;
		}
		// The requestesd controller or function does not exist
		// therefore let the error controller handle it
		else if ( !self::_isCallable($controller, $method) )
		{
			Log::setWarn('Callback', 'Wrong request: class &lt;'.$controller.'&gt; and method &lt;'.$method.'&gt; not found.');

			/**
			 * Speed-Performance Note:
			 * Here is a rare case
			 * If you enter the ERROR-CONTROLLER in the URL, but a wrong method name
			 * Then, the controller will have been loaded (require()) by the auto-loader already.
			 * and we cannot 'require' it again here.
			 * As require_once is too slow for us, we need to check against it
			 * and decide whether to load it or not
			 */
			if ( $controller != $GLOBALS['ERROR_CONTROLLER'] )
			{
				require(CONTROLLER.DS.$GLOBALS['ERROR_CONTROLLER'].'.php');
				require(MODEL.DS.$GLOBALS['ERROR_CONTROLLER'].'Model.php');
			}

			self::$object = array(
				'class'		=> $GLOBALS['ERROR_CONTROLLER'],
				'method'	=> $GLOBALS['ERROR_METHOD'],
				'params'	=> array(Url::$request),
			);
			return true;
		}

		// Security pre-caution:
		// Make sure that it is a class that has 'Controller.php' as his mother and not
		// any other user-defined class
		else if ( !self::_isControllerClass($controller) )
		{
			Log::setWarn('Callback', 'Wrong request: class &lt;'.$controller.'&gt; is not a Controller class.');

			require(CONTROLLER.DS.$GLOBALS['ERROR_CONTROLLER'].'.php');
			require(MODEL.DS.$GLOBALS['ERROR_CONTROLLER'].'Model.php');

			self::$object = array(
				'class'		=> $GLOBALS['ERROR_CONTROLLER'],
				'method'	=> $GLOBALS['ERROR_METHOD'],
				'params'	=> array(Url::$request),
			);
			return true;
		}

		// Security pre-caution:
		// All functions defined in 'Controller.php' itself are inherited to
		// the xxxController Class, but are not allowed to be called
		else if ( self::_methodIsForbidden($method) )
		{
			Log::setWarn('Callback', 'Method &lt;'.$method.'&gt; is not allowed to be called');

			require(CONTROLLER.DS.$GLOBALS['DEFAULT_CONTROLLER'].'.php');
			require(MODEL.DS.$GLOBALS['DEFAULT_CONTROLLER'].'Model.php');

			self::$object = array(
				'class'		=> $GLOBALS['ERROR_CONTROLLER'],
				'method'	=> $GLOBALS['ERROR_METHOD'],
				'params'	=> array(Url::$request),
			);
			return true;
		}

		// OK:
		// Everyhing went fine
		else
		{
			self::$object = array(
				'class'		=> $controller,
				'method'	=> $method,
				'params'	=> $params,
			);
			return true;
		}
	}



	/* ******************************************** RETURN THE OBJECT ********************************************/
	public static function getObject()
	{
		return self::$object;
	}




	/************************************************** PRIVATE FUNCTIONS **************************************************/

	/**
	 *
	 * Does the class or function actually exist
	 * and are they 'public', so they can be called?
	 *
	 */
	private static function _isCallable($class, $method)
	{
		if (!class_exists($class))
		{
			Log::setWarn('Callback', 'class &lt;'.$class.'&gt; does not exist.');
			return false;
		}
		if ( !method_exists($class, $method) )
		{
			Log::setWarn('Callback', 'method &lt;'.$method.'&gt; does not exist in class &lt;'.$class.'&gt;');
			return false;
		}
		/*
		 * Only allow public functions to be called
		 *
		 * produces E_STRICT WARNING on older PHP Versions, so we need the '@'
		 */
		if ( !@is_callable(array($class, $method)) )
		{
			Log::setWarn('Callback', 'method &lt;'.$method.'&gt; is not publically callable in class &lt;'.$class.'&gt;');
			return false;
		}

		return true;
	}

	/**
	 *
	 * Does the class extend 'Controller.php'?
	 * Otherwise it is a class/function from somewhere
	 * else and we do not want it to be executed
	 *
	 */
	private static function _isControllerClass($class)
	{
		if ( get_parent_class($class) != 'PageController' )
		{
			return false;
		}
		return true;
	}

	/**
	*
	* All methods in 'Controller.php' itself are
	* forbidden.
	*
	* E.g.: We do not want the user to call getView
	* 		or even worse set() to override variables
	*
	*/
	private static function _methodIsForbidden($method)
	{
		// All methods in the framework Controller itself
		// are not allowed to be called
		$pageCtlMethods		= get_class_methods('PageController');
		$baseCtlMethods		= get_class_methods('BaseController');
		$blacklistMethods	= array_merge($pageCtlMethods, $baseCtlMethods);

		foreach ($blacklistMethods as $forbidden)
		{
			if ($method == $forbidden)
			{
				return true;
			}
		}
		return false;
	}
	
	
	private static function _blockMethodIsCallable($blockCtrl, $blockMthd)
	{
		// TODO: security check
		return true;
	}
}


?>