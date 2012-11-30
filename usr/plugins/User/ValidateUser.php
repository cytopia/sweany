<?php
class ValidateUser extends \Sweany\aBootTemplate
{
	public static function initialize($options = null)
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

		// Validate Existance of Username and Password Length config defines
		if ( !Config::exists('userNameMinLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>userNameMinLen</i> is not defined. Add Config::set(\'userNameMinLen\', 5, \'user\'); to config.php';
			return false;
		}
		if ( !Config::exists('userNameMaxLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>userNameMaxLen</i> is not defined. Add Config::set(\'userNameMaxLen\', 15, \'user\'); to config.php';
			return false;
		}
		if ( !Config::exists('passwordMinLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>passwordMinLen</i> is not defined. Add Config::set(\'passwordMinLen\', 6, \'user\'); to config.php';
			return false;
		}
		if ( !Config::exists('passwordMaxLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>passwordMaxLen</i> is not defined. Add Config::set(\'passwordMaxLen\', 40, \'user\'); to config.php';
			return false;
		}

		// Validate correct values of Username and Password Length defines
		if ( !is_integer(Config::get('userNameMinLen', 'user'))  || Config::get('userNameMinLen', 'user') < 1 )
		{
			self::$error  = '<b>User Plugin:</b> <i>userNameMinLen</i> must be a positive integer. See config.php';
			return false;
		}
		if ( !is_integer(Config::get('userNameMaxLen', 'user'))  || Config::get('userNameMaxLen', 'user') < 1 )
		{
			self::$error  = '<b>User Plugin:</b> <i>userNameMaxLen</i> must be a positive integer. See config.php';
			return false;
		}
		if ( !is_integer(Config::get('passwordMinLen', 'user'))  || Config::get('passwordMinLen', 'user') < 1 )
		{
			self::$error  = '<b>User Plugin:</b> <i>passwordMinLen</i> must be a positive integer. See config.php';
			return false;
		}
		if ( !is_integer(Config::get('passwordMaxLen', 'user')) || Config::get('passwordMaxLen', 'user') < 1 )
		{
			self::$error  = '<b>User Plugin:</b> <i>passwordMaxLen</i> must be a positive integer. See config.php';
			return false;
		}

		// Validate stupid users
		if ( Config::get('userNameMaxLen', 'user') <= Config::get('userNameMinLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b><br/>Noo! You are doing it wrong.<br/><i>userNameMinLen</i> must be smaller than <i>userNameMaxLen</i>. See config.php';
			return false;
		}
		if ( Config::get('passwordMaxLen', 'user') <= Config::get('passwordMinLen', 'user') )
		{
			self::$error  = '<b>User Plugin:</b><br/>Noo! You are doing it wrong.<br/><i>passwordMinLen</i> must be smaller than <i>passwordMaxLen</i>. See config.php';
			return false;
		}

		// Validate even more stupid users
		if ( Config::get('passwordMinLen', 'user') < 6 )
		{
			self::$error  = '<b>User Plugin:</b><br/>Noo! You are doing it even wronger!<br/><i>passwordMinLen</i> should be greater than 6 characters. PLEASE!';
			return false;
		}
		
		// Validate disabled Registration
		if ( !Config::exists('disableRegistration', 'user') )
		{
			self::$error  = '<b>User Plugin:</b> <i>disableRegistration</i> is not defined. Add Config::set(\'disableRegistration\', 0, \'user\'); to config.php';
			return false;
		}
		if ( !is_numeric(Config::get('disableRegistration', 'user')) || !( Config::get('disableRegistration', 'user') == 0 || Config::get('disableRegistration', 'user') == 1 ) )
		{
			self::$error  = '<b>User Plugin:</b> <i>disableRegistration</i> must be either set to <b>0</b> or <b>1</b> in config.php';
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
