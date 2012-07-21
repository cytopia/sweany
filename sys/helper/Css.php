<?php


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