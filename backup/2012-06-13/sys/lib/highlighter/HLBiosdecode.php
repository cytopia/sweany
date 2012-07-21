<?php

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