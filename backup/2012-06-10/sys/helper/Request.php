<?php
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