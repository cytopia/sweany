<?php


Class Css
{
	private $css_files	= array();


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public function addFile($file)
	{
		$size = sizeof($this->css_files);

		$this->css_files[$size] = '<link rel="stylesheet" type="text/css" href="'.$file.'" />';
	}

	public function getFiles()
	{
		$code	= '';

		foreach ($this->css_files as $file)
			$code .= "\t".$file."\n";

		return $code;
	}
}
?>