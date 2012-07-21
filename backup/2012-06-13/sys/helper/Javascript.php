<?php


Class Javascript
{
	private $js_files	= array();
	private $js_funcs	= array();
	private $js_vars	= array();
	private $onPageLoad	= null;


	/********************************************************* A C T I O N   F U N C T I O N S *********************************************************/

	public function setOnPageLoadFunction($functionName)
	{
		$this->onPageLoad = $functionName;
	}
	public function getOnPageLoadFunction()
	{
		if (!is_null($this->onPageLoad))
		{
			return ' onload="'.$this->onPageLoad.'"';
		}
		return '';
	}

	// Add global variables
	public function addVars($vars = array())
	{
		$size = sizeof($this->js_vars);
		foreach ($vars as $var => $value)
		{
			if ( is_numeric($value) )
				$this->js_vars[$size] = 'var '.$var.'='.$value.';';
			else
				$this->js_vars[$size] = 'var '.$var.'=\''.addslashes($value).'\';';
			
			$size++;
		}
	}

	public function addFunction($function)
	{
		$size = sizeof($this->js_funcs);
		$this->js_funcs[$size] = $function;
	}

	public function addFile($file)
	{
		$size = sizeof($this->js_files);
		$this->js_files[$size] = '<script type="text/javascript" src="'.$file.'"></script>';
	}

	public function getVars()
	{
		if ( !sizeof($this->js_vars) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( $this->js_vars as $var )
			$code .= $var;

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public function getFunctions()
	{
		if ( !sizeof($this->js_funcs) )
			return '';

		$pre	= '<script type="text/javascript">';
		$post	= '</script>';
		$code	= '';

		foreach ( $this->js_funcs as $function )
			$code .= $function."\n";

		return "\t".$pre."\n".$code."\n\t".$post."\n";
	}

	public function getFiles()
	{
		$code	= '';

		foreach ($this->js_files as $file)
			$code .= "\t".$file."\n";

		return $code;
	}


	/********************************************************* A J A X    F U N C T I O N S *********************************************************/

	public function loadLiveSearch($callback_url, $element_id)
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
		$this->addFunction($js);
	}



}
?>