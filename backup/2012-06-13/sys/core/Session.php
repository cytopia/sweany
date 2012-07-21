<?php
/**
*
* This core module will activate the Session
*
*/
class Session extends CoreTemplate
{

	/* ******************************************** OVERRIDE INITIALIZE ********************************************/

	public static function initialize()
	{
		if ( session_start() )
		{
			return true;
		}
		else
		{
			switch (session_status())
			{
				case PHP_SESSION_DISABLED:	self::$error = 'PHP_SESSION_DISABLED';	return false; break;
				case PHP_SESSION_NONE : 	self::$error = 'PHP_SESSION_NONE';		return false; break;
				case PHP_SESSION_ACTIVE :	self::$error = 'PHP_SESSION_ACTIVE';	return false; break;
				default: self::$error = 'Unknown Session Error '; return false;
			}
		}
	}



	/* ******************************************** GET ********************************************/

	public static function getId()
	{
		return ( session_id() );
	}

	public static function get($key)
	{
		return ( isset($_SESSION[$key]) ) ? $_SESSION[$key] : NULL;
	}

	public static function getSubKey($section, $key, $position = NULL)
	{
		if ( $position )
		{
			$tmp = isset($_SESSION[$section][$position][$key]) ? $_SESSION[$section][$position][$key] : array();
		}
		else
		{
			$tmp = isset($_SESSION[$section][$key]) ? $_SESSION[$section][$key] : array();
		}

		return ( $tmp );
	}



	/* ******************************************** SET ********************************************/
	public static function set($key, $val)
	{
		$_SESSION[$key] = $val;
	}



	/* ******************************************** CHECK ********************************************/
	public static function exists($key)
	{
		return isset($_SESSION[$key]);
	}



	/* ******************************************** DELETE ********************************************/

	public static function del($key)
	{
		if ( isset($_SESSION[$key]) )
			unset($_SESSION[$key]);
	}

	public static function delSubKey($key, $sub_key)
	{
		if ( isset($_SESSION[$key][$sub_key]) )
			unset($_SESSION[$key][$sub_key]);
	}

	public static function destroy()
	{
		// Delete the whole session and the session cookie (with its file)
		if (ini_get("session.use_cookies"))
		{
			$params = session_get_cookie_params();
			setcookie(
				session_name(),
				'',
				time() - 42000,
				$params['path'],
				$params['domain'],
				$params['secure'],
				$params['httponly']
			);
		}
		session_destroy();
	}
}

?>