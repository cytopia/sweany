<?php


Class Javascript
{
	private static $js_files	= array();
	private static $js_funcs	= array();
	private static $js_vars		= array();
	private static $onPageLoad	= null;


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public static function setOnPageLoadFunction($functionName)
	{
		self::$onPageLoad = $functionName;
	}
	public static function getOnPageLoadFunction()
	{
		if (!is_null(self::$onPageLoad))
		{
			return ' onload="'.self::$onPageLoad.'"';
		}
		return '';
	}

	// Add global variables
	public static function addVars($vars = array())
	{
		$size = sizeof(self::$js_vars);
		foreach ($vars as $var => $value)
		{
			if ( is_numeric($value) )
				self::$js_vars[$size] = 'var '.$var.'='.$value.';';
			else
				self::$js_vars[$size] = 'var '.$var.'=\''.addslashes($value).'\';';
			
			$size++;
		}
	}

	public static function addFunction($function)
	{
		$size = sizeof(self::$js_funcs);
		self::$js_funcs[$size] = $function;
	}

	public static function addFile($file)
	{
		$size = sizeof(self::$js_files);
		self::$js_files[$size] = '<script type="text/javascript" src="'.$file.'"></script>';
	}

	public static function getVars()
	{
		if ( !sizeof(self::$js_vars) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( self::$js_vars as $var )
			$code .= $var;

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public static function getFunctions()
	{
		if ( !sizeof(self::$js_funcs) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( self::$js_funcs as $function )
			$code .= $function."\n";

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public static function getFiles()
	{
		$code	= '';

		foreach (self::$js_files as $file)
			$code .= "\t".$file."\n";

		return $code;
	}


	/********************************************************* A J A X    F U N C T I O N S *********************************************************/

	public static function loadLiveSearch($callback_url, $element_id)
	{
		$js = <<<EOD
		function showResult(str)
		{
			if (str.length==0)
			{
				document.getElementById("$element_id").innerHTML="";
				document.getElementById("$element_id").style.border="0px";
				return;
			}
			if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
				if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					document.getElementById("$element_id").innerHTML=xmlhttp.responseText;
					document.getElementById("$element_id").style.border="1px solid #A5ACB2";
					document.getElementById("$element_id").style.width="200px";
				}
			}
			xmlhttp.open("GET","$callback_url"+str,true);
			xmlhttp.send();
		}
EOD;
		self::$addFunction($js);
	}



}
?>