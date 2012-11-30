<?php
class ValidateGuestbook extends \Sweany\aBootTemplate
{
	public static function initialize($options = null)
	{
		if ( !$GLOBALS['LANGUAGE_ENABLE'] )
		{
			self::$error  = '<b>Guestbook Plugin:</b> Requires LANGUAGE Module.';
			return false;
		}

		if ( !$GLOBALS['SQL_ENABLE'] )
		{
			self::$error  = '<b>Guestbook Plugin:</b> Requires SQL Module.';
			return false;
		}

		// Validate Layout if exists
		if ( Config::exists('layout', 'guestbook') )
		{
			$layout = Config::get('layout', 'guestbook');

			if ( !is_array($layout) )
			{
				self::$error  = '<b>Guestbook Plugin:</b> <i>layout</i> has to be an array. See config.php comments';
				return false;
			}
			if ( !isset($layout[0]) || !isset($layout[1]) )
			{
				self::$error  = '<b>Guestbook Plugin:</b> <i>layout</i> has to have at least 2 array keys. See config.php comments';
				return false;
			}
			if ( !is_file(USR_LAYOUTS_PATH.DS.$layout[0].'.php') )
			{
				self::$error  = '<b>Guestbook Plugin:</b> <i>layout: '.$layout[0].'.php</i> does not exist in '.USR_LAYOUTS_PATH;
				return false;
			}
		}
		return true;
	}
}
