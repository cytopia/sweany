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
 * @package		sweany.core.lib
 * @author		Patu <pantu39@gmail.com>
 * @license		GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @version		0.7 2012-07-29 13:25
 *
 *
 * Block Helper
 *
 * This class will take care about rendering
 * and returning a specified block anywhere you need it.
 *
 */
Class Blocks
{
	/**
	 *
	 * Returns a rendered block
	 *
	 * This function can be called anywhere
	 * (controller, model or view)
	 *
	 * @param string $blockPluginName
	 * @param string $blockControllerName
	 * @param string $blockMethodName
	 * @param array  $blockMethodParams
	 * @return mixed
	 * 		$block['ret']	// return value of the block function
	 *		$block['html']	// rendered block
	 */
	public static function get($blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams = array())
	{
		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )
			$start = getmicrotime();

		$output = Render::block($blockPluginName, $blockControllerName, $blockMethodName, $blockMethodParams);

		if ( \Core\Init\CoreSettings::$showFwErrors > 2 || \Core\Init\CoreSettings::$logFwErrors > 2 )
		{
			$plugin = ($blockPluginName) ? $blockPluginName.':' : '';
			$params	= '';//@implode(',', $blockMethodParams);
			SysLog::i('Block', '(rendered) from: '.$plugin.$blockControllerName.'->'.$blockMethodName.'('.$params.')', null, $start);
		}

		return $output;
	}
}

