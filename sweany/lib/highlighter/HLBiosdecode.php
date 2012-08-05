<?php
/**
 * Highlighter (linux's biosdecode)
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
class HLBiosdecode extends Highlight
{
	public static function colorize($string)
	{
		// keywords
		$keys0 = array(
			'SMBIOS 2.4 present.', 'ACPI 1.0 present.', 'BIOS32 Service Directory present.',
			'PNP BIOS 1.0 present.', 'PCI Interrupt Routing 1.0 present.');

		$bad = array(
			'Not Supported'
		);

		$string = self::_keysBold($string, $keys0, 'grayblue');
		$string = self::_keysNormal($string, $bad, 'red');


		// numbers
		$string = self::_hex($string, 'orange');

		return Strings::removeEmptyLines($string);
	}
}

?>