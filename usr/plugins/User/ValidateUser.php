<?php
class ValidateUser extends \Core\Init\CoreAbstract
{
	public static function initialize()
	{
		if ( !$GLOBALS['LANGUAGE_ENABLE'] )
		{
			self::$error  = '<b>User Plugin:</b> Requires LANGUAGE Module.';
			return false;
		}

		if ( !$GLOBALS['SQL_ENABLE'] )
		{
			self::$error  = '<b>User Plugin:</b> Requires SQL Module.';
			return false;
		}
		if ( !$GLOBALS['USER_ENABLE'] )
		{
			self::$error  = '<b>User Plugin:</b> Requires USER Module.';
			return false;
		}

		// Validate Layout if exists
		if ( Config::exists('layout', 'user') )
		{
			$layout = Config::get('layout', 'user');

			if ( !is_array($layout) )
			{
				self::$error  = '<b>User Plugin:</b> <i>layout</i> has to be an array. See config.php comments';
				return false;
			}
			if ( !isset($layout[0]) || !isset($layout[1]) )
			{
				self::$error  = '<b>User Plugin:</b> <i>layout</i> has to have at least 2 array keys. See config.php comments';
				return false;
			}
			if ( !is_file(USR_LAYOUTS_PATH.DS.$layout[0].'.php') )
			{
				self::$error  = '<b>User Plugin:</b> <i>layout: '.$layout[0].'.php</i> does not exist in '.USR_LAYOUTS_PATH;
				return false;
			}
		}


		// Check user's config.php
		if ( !Config::exists('acceptTermsOnRegister','user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>acceptTermsOnRegister</i> is missing in config.php';
			return false;
		}
		if ( !Config::exists('termsUrl','user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>termsUrl</i> is missing in config.php';
			return false;
		}
		if ( !Config::exists('policyUrl','user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>policyUrl</i> is missing in config.php';
			return false;
		}

		$acceptTerms = Config::get('acceptTermsOnRegister','user');
		if ( $acceptTerms != 0 && $acceptTerms != 1 )
		{
			self::$error  = '<b>User Plugin:</b> <i>acceptTermsOnRegister</i> has invalid value (only 0 or 1 are allowed) in config.php';
			return false;
		}

		if ( $acceptTerms )
		{
			$terms = Config::get('termsUrl','user');
			if ( !strlen($terms) )
			{
				self::$error  = '<b>User Plugin:</b> If you have enabled <i>acceptTermsOnRegister</i> you need an url for <i>termsUrl</i> in config.php';
				return false;
			}
			$policy = Config::get('policyUrl','user');
			if ( !strlen($policy) )
			{
				self::$error  = '<b>User Plugin:</b> If you have enabled <i>acceptTermsOnRegister</i> you need an url for <i>policyUrl</i> in config.php';
				return false;
			}
		}

		return true;
	}
}
