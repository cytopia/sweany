<?php
class ValidateForums extends \Core\Init\CoreAbstract
{
	public static function initialize()
	{
		if ( !$GLOBALS['LANGUAGE_ENABLE'] )
		{
			self::$error  = '<b>Forums Plugin:</b> Requires LANGUAGE Module.';
			return false;
		}

		if ( !$GLOBALS['SQL_ENABLE'] )
		{
			self::$error  = '<b>Forums Plugin:</b> Requires SQL Module.';
			return false;
		}
		if ( !$GLOBALS['USER_ENABLE'] )
		{
			self::$error  = '<b>Forums Plugin:</b> Requires USER Module.';
			return false;
		}
		if ( !$GLOBALS['USER_ONLINE_COUNT_ENABLE'] )
		{
			self::$error  = '<b>Forums Plugin:</b> Requires USER ONLINE COUNT Module.';
			return false;
		}

		// Validate Layout if exists
		if ( Config::exists('layout', 'forum') )
		{
			$layout = Config::get('layout', 'forum');

			if ( !is_array($layout) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>layout</i> has to be an array. See config.php comments';
				return false;
			}
			if ( !isset($layout[0]) || !isset($layout[1]) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>layout</i> has to have at least 2 array keys. See config.php comments';
				return false;
			}
			if ( !is_file(USR_LAYOUTS_PATH.DS.$layout[0].'.php') )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>layout: '.$layout[0].'.php</i> does not exist in '.USR_LAYOUTS_PATH;
				return false;
			}
		}


		// Check user's config.php
		if ( !Config::exists('loginCtl', 'forum') )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>loginCtl</i> is missing in config.php';
			return false;
		}
		if ( !Config::exists('loginMethod', 'forum') )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>loginMethod</i> is missing in config.php';
			return false;
		}
		if ( !Config::exists('registerCtl', 'forum') )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>registerCtl</i> is missing in config.php';
			return false;
		}
		if ( !Config::exists('registerMethod', 'forum') )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>registerMethod</i> is missing in config.php';
			return false;
		}

		// Extra's
		if ( !Config::exists('userProfileLinkEnable', 'forum') )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>userProfileLinkEnable</i> is missing in config.php';
			return false;
		}
		if ( Config::get('userProfileLinkEnable', 'forum') !== true && Config::get('userProfileLinkEnable', 'forum') !== false  )
		{
			self::$error  = '<b>Forum Plugin:</b> <i>userProfileLinkEnable</i> can only be true or false in config.php';
			return false;
		}

		// Show Profile Link?
		if ( Config::get('userProfileLinkEnable', 'forum') )
		{
			if ( !Config::exists('userProfileCtl', 'forum') )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>userProfileCtl</i> is needed when userProfileLink is enabled in config.php';
				return false;
			}
			if ( !strlen(Config::get('userProfileCtl', 'forum')) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>userProfileCtl</i> must have a value when userProfileLink is enabled in config.php';
				return false;

			}
			if ( !Config::exists('userProfileMethod', 'forum') )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>userProfileMethod</i> is needed when userProfileLink is enabled in config.php';
				return false;
			}
			if ( !strlen(Config::get('userProfileMethod', 'forum')) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>userProfileMethod</i> must have a value when userProfileLink is enabled in config.php';
				return false;
			}
		}
		// Show Write Message Link?
		if ( Config::get('writeMessageLinkEnable', 'forum') )
		{
			if ( !Config::exists('writeMessageCtl', 'forum') )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>writeMessageCtl</i> is needed when writeMessageLinkEnable is enabled in config.php';
				return false;
			}
			if ( !strlen(Config::get('writeMessageCtl', 'forum')) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>writeMessageCtl</i> must have a value when writeMessageLinkEnable is enabled in config.php';
				return false;

			}
			if ( !Config::exists('writeMessageMethod', 'forum') )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>writeMessageMethod</i> is needed when writeMessageLinkEnable is enabled in config.php';
				return false;
			}
			if ( !strlen(Config::get('writeMessageMethod', 'forum')) )
			{
				self::$error  = '<b>Forum Plugin:</b> <i>writeMessageMethod</i> must have a value when writeMessageLinkEnable is enabled in config.php';
				return false;
			}
		}
		return true;
	}
}
