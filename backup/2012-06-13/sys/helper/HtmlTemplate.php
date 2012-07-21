<?php
Class HtmlTemplate
{
	private $xmlNs		= null;
	private $htmlAttr	= null;
	private $headPrefix	= null;
	private $title		= null;
	private $metaTags	= null;
	private $keywords	= null;
	private $description= null;
	private $redirect	= null;


	/******************************************************** HTML ********************************************************/

	public function addXmlNameSpace($subNs = null, $value)
	{
		$this->xmlNs .= !is_null($subNs) ? ' xmlns:'.$subNs.'="'.$value.'"' : ' xmlns="'.$value.'"';
	}
	public function getXmlNameSpace()
	{
		return $this->xmlNs;
	}

	// adds an attribute to <html ...here... >
	public function addHtmlAttribute($attribute = null)
	{
		$this->htmlAttr = $attribute;
	}
	public function getHtmlAttribute()
	{
		return $this->htmlAttr;
	}


	/******************************************************** HEAD ********************************************************/
	public function addHeadPrefix($prefix)
	{
		$this->headPrefix .= ' '.$prefix;
	}
	public function getHeadPrefix()
	{
		return $this->headPrefix;
	}





	/******************************************************** META ********************************************************/

	public function addMetaTag($tag)
	{
		$this->metaTags .= "\t".$tag."\n";
	}
	public function getMetaTags()
	{
		return $this->metaTags;
	}


	public function setRedirect($url, $delay = 5)
	{
		$this->addMetaTag('<meta http-equiv="refresh" content="'.$delay.'; url='.$url.'" />');
	}



	public function setTitle($title)
	{
		$this->title = htmlentities($title);
	}
	public function getTitle()
	{
		return !is_null($this->title) ? $this->title : $GLOBALS['HTML_DEFAULT_PAGE_TITLE'];
	}


	public function setKeywords($keywords)
	{
		$this->keywords = htmlentities($keywords);
	}
	public function getKeywords()
	{
		return !is_null($this->keywords) ?  $this->keywords : $GLOBALS['HTML_DEFAULT_PAGE_KEYWORDS'];
	}


	public function setDescription($description)
	{
		$this->description = htmlentities($description);
	}
	public function getDescription()
	{
		return !is_null($this->description) ? $this->description : $GLOBALS['HTML_DEFAULT_PAGE_DESCRIPTION'];
	}
}

?>