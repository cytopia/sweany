<?php

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