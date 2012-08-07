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
 * Sweaby is distributed in the hope that it will be useful,
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
 * Highlighter (freebsd's usbconfig)
 */
class HLUsbconfig extends Highlight
{
	public static function colorize($string)
	{
		// keywords
		$keys0 = array('Configuration index', ' Interface ', 'Endpoint ');

		$string = self::_keysBold($string, $keys0, 'yellow');
		$string = self::_keysBold($string, array("\nugen", 'ugen'), 'white');
//		$string = self::_keysNormal($string, $keys1, 'grayblue');
//		$string = self::_keysNormal($string, $keys0, 'white');
//		$string = self::_keysBold($string, $bad, 'red');
//		$string = self::_keysNormal($string, $good, 'green');

		// numbers
		$string = self::_hex($string, 'orange');


		return Strings::removeEmptyLines($string);
	}
}

?>