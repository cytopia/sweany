<?php
/**
 * Highlighter (linux's dmidecode)
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
class HLDmidecode extends Highlight
{
	private static $sqlKeys = array();


	public static function colorize($string)
	{
		// keywords
		$keys0 = array("\nHandle ", 'DMI type', 'Table at ', 'End Of Table');
		$keys1 = array(
			"\nBIOS Information", "\nSystem Information", "\nBase Board Information", "\nChassis Information",
			"\nProcessor Information", "\nMemory Controller Information", "\nMemory Module Information", "\nCache Information",
			"\nPort Connector Information", "\nSystem Slot Information", "\nBIOS Language Information", "\nPhysical Memory Array",
			"\nMemory Device Mapped Address", "\nMemory Array Mapped Address", "\nMemory Device", "\nSystem Boot Information",
			"\nUnknown Type",
		);
		$keys2 = array(
			'Vendor:', 'Version:', 'Runtime Size:', 'ROM Size:', 'Characteristics:', 'Release Date:',
			'Manufacturer:', 'Product Name:', 'Wake-up Type:', 'Family:', 'Lock:','Asset Tag:', 'Boot-up State:',
			'Power Supply State:', 'Thermal State:', 'Security Status:', 'OEM Information:', 'Socket Designation:', 'ID:',
			'Signature:', 'Flags:', 'External Clock:', 'Max Speed:', 'Current Speed:', 'Upgrade:',
			'L1 Cache Handle:', 'L2 Cache Handle:', 'L3 Cache Handle:', 'Part Number:', 'Error Detecting Method:',
			'Enabled Error Correcting Capabilities:', 'Error Correcting Capabilities:',
			'Supported Interleave:', 'Current Interleave:', 'Maximum Memory Module Size:', 'Maximum Total Memory Size:', 'Supported Speeds:',
			'Supported Memory Types:', 'Memory Module Voltage:', 'Associated Memory Slots:', 'Bank Connections:',
			'Installed Size:', 'Enabled Size:', 'Error Status:', 'Status:', 'Configuration:', 'Operational Mode:', 'Location:',
			'Maximum Size:', 'Supported SRAM Types:', 'Installed SRAM Type:', 'Error Correction Type:', 'System Type:',
			'Associativity:', 'Internal Reference Designator:', 'Internal Connector Type:', 'External Reference Designator:',
			'External Connector Type:', 'Port Type:', 'Designation:', 'Current Usage:', 'Length:',
			'Language Description Format:', 'Installable Languages:', 'Currently Installed Language:',
			'Maximum Capacity:', 'Error Information Handle:', 'Number Of Devices:', 'Physical Array Handle:', 'Array Handle:',
			'Total Width:',	'Data Width:', 'Form Factor:', 'Bank Locator:', 'Locator:', 'Type Detail:', 'Starting Address:', 'Ending Address:',
			'Range Size:', 'Partition Width:', 'Physical Device Handle:', '  Memory Array Mapped Address Handle:', 'Partition Row Position:',
			'Header and Data:', 'Strings:',

			'Address:', 'Voltage:', 'Type:', 'Speed:', 'Use:', 'Size:', 'Set:',
		);
		$bad	= array('Not Present', 'Not Provided', 'Not Installed', 'Not Socketed', 'No Module Installed', 'None');
		$good	= array(' is supported', " is provided", ' are supported', ' is allowed', ' is upgradeable', ' Enabled', ' Available');

		$string = self::_keysBold($string, $keys0, 'white');
		$string = self::_keysBold($string, $keys1, 'grayblue');
		$string = self::_keysNormal($string, $keys2, 'yellow');

		$string = self::_keysNormal($string, $bad, 'red');
		$string = self::_keysBold($string, $good, 'green');

		// numbers
		$string = self::_hex($string, 'orange');

		return Strings::removeEmptyLines($string);
	}
}

?>