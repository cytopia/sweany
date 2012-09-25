<?php
/**
 * Sweany MVC PHP framework
 * Copyright (C) 2011-2012 Patu.
 *
 * Sweany is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Sweany is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Sweany. If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright	Copyright 2011-2012, Patu
 * @link		none yet
 * @package		sweany.core.validator
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-08-08 11:25
 *
 *
 * This (optional) core will validate various settings of
 * the framework itself.
 */
namespace Sweany;
class Validate09Plugins extends aBootTemplate
{
	/* ******************************************** OVERRIDE INITIALIZE ********************************************/
	public static function initialize($options = null)
	{
		if ( !self::_checkPlugins() )
		{
			echo '<h1>Validation Error: Plugins</h2>';
			return false;
		}
		return true;
	}


	/* ******************************************** VALIDATORS ********************************************/

	private static function _checkPlugins()
	{
		if ( $handle = opendir(USR_PLUGINS_PATH) )
		{
			while ( false !== ($file = readdir($handle)) )
			{
				if ( $file != '.' && $file != '..' && is_dir(USR_PLUGINS_PATH.DS.$file) )
				{
					$validatorClass	= 'Validate'.$file;
					$validatorPath	= USR_PLUGINS_PATH.DS.$file.DS.$validatorClass.'.php';
					if ( is_file($validatorPath) )
					{
						// Load Plugin Config before validation
						if ( is_file(USR_PLUGINS_PATH.DS.$file.DS.'config.php') )
						{
							require_once(USR_PLUGINS_PATH.DS.$file.DS.'config.php');
						}

						// Load Validator
						require($validatorPath);

						if ( !$validatorClass::initialize() )
						{
							return false;
						}
					}
				}
			}
		}
		return true;
	}
}
