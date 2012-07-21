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
		return isset(self::$urlParams[0]) ? self::$urlParams[0] : NULL;
	}

	public static function getMethod()
	{
		return isset(self::$urlParams[1]) ? self::$urlParams[1] : 'index';
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

	/**
	 *	Determine whether the url request is a route to a block.
	 *  Only if it is the form of
	 *
	 * 	_api/<block>/<block_ctrl>/<block_method>/<params...>
	 *
	 */
	public static function isBlockRoute()
	{
		return ( isset(self::$urlParams[0]) && isset(self::$urlParams[1]) && isset(self::$urlParams[2]) && isset(self::$urlParams[3]) && self::$urlParams[0] == '_api' );
	}
	
	public static function getBlockPath()
	{
		return BLOCK.DS.self::$urlParams[1];
	}
	public static function getBlockController()
	{
		return self::$urlParams[2];
	}
	public static function getBlockMethod()
	{
		return self::$urlParams[3];
	}
	public static function getBlockParams()
	{
		$params = array();
		for ($i=4; $i<sizeof(self::$urlParams); $i++)
		{
			$params[] = self::$urlParams[$i];
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