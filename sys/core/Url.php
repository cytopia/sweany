<?php
/**
*
* This core module will extract
* controller, function and params from the given URL
*
* It also takes care about encoding/decoding function parameter values
*
*/
class Url extends CoreTemplate
{
	/* ******************************************** VARIABLES ********************************************/

	public static $request		= null;

	private static $urlParams	= null;



	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	public static function initialize()
	{
		self::$request = $_SERVER['REQUEST_URI'];

		// remove first slah
		if ( isset(self::$request[0]) &&  self::$request[0] == DS )
		{
			self::$request = substr(self::$request, 1, strlen(self::$request));
		}

		// if using $html->l() it will be double url encoded and / \ will also be double encoded
		// so we have to revert it, after exploding
		$params 	= explode('/', self::$request);
		$encoded	= array();

		// encode params
		foreach ($params as $param)
			if ( strlen($param) > 0 )
				$encoded[] = self::_encodeParam($param);

		self::$urlParams = $encoded;

		return true;
	}


	/* ******************************************** ACTIONS ********************************************/

	public static function getController()
	{
		return isset(self::$urlParams[0]) ? self::$urlParams[0] : null;
	}

	public static function getMethod()
	{
		// if no method has been supplied as url parameter
		// try the default method (usually 'index'), but can be set in config
		return isset(self::$urlParams[1]) ? self::$urlParams[1] : $GLOBALS['ANY_CONTROLLER_DEFAULT_METHOD'];
	}

	public static function getParams()
	{
		$params	= array();

		for ($i=2; $i<sizeof(self::$urlParams); $i++)
		{
			$params[]	= self::$urlParams[$i];
		}
		return $params;
	}



	public static function changeSingleParam($param_position, $value)
	{
		$arr	= self::getParams();
		$size 	= (sizeof($arr) >= $param_position) ? sizeof($arr) : $param_position;
		$params	= array();

		// fill up missing params with 0 if not set before $param_position
		for($i=0; $i<$size; $i++)
		{
			if ( !isset($arr[$i]) && !strlen($arr[$i]) )
				$params[$i] = 0;
			else
				$params[$i] = $arr[$i];
		}
		$params[$param_position-1] = self::_encodeParam($value);

		return $params;
	}



	/* ******************************************** PRIVATES ********************************************/

	private static function _encodeParam($value)
	{
		return ($value);
	}
}