<?php
/**
 * Request Helper
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
 * @package		sweany.helper
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.2 2012-07-29 13:25
 *
 */
class Request
{
	/**
	 *
	 *  Informs whether or not the controller request
	 *  was done via ajax.
	 */
	public static function isAjax()
	{
		return ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
	}

	public static function isPost()
	{}
	public static function isGet()
	{}
	public static function isPut()
	{}
	public static function isDelete()
	{}
	public static function isSSL()
	{}
	public static function isXml()
	{}
	public static function isRss()
	{}
	public static function isAtom()
	{}
	public static function isMobile()
	{}
	public static function isWap()
	{}


}