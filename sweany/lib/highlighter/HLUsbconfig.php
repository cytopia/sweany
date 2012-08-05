<?php
/**
 * Highlighter (freebsd's usbconfig)
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
 * @package		sweany.lib
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
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