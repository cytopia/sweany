<?php
/**
 * CSS Helper
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
 * @package		sweany.helper
 * @author		Patu
 * @license		MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @version		0.7 2012-07-29 13:25
 *
 */
Class Css
{
	private static $css_files	= array();


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public static function addFile($file)
	{
		$size = sizeof(self::$css_files);

		self::$css_files[$size] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
	}

	public static function getFiles()
	{
		$code	= '';

		foreach (self::$css_files as $file)
			$code .= "\t".$file."\n";

		return $code;
	}
}
?>